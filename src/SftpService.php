<?php

namespace devtoolbox\sftplib;

use phpseclib\Net\SFTP;
use phpseclib\Crypt\RSA;

class SftpService implements Connection
{
    protected $connection;
    protected $credentials;
    protected $path = '/';
    protected $fileName = 'blank';
    protected $tempFileName;

    public function __construct()
    {
        $this->tempFileName = sprintf('%s.uploading', $this->fileName);
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        $this->tempFileName = sprintf('%s.uploading', $fileName);
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getTempFilename()
    {
        return $this->tempFileName;
    }

    public function create(Credentials $credentials, $timeout = 10)
    {
        $this->credentials = $credentials;

        $this->connection = new SFTP($this->credentials->getHost(), $this->credentials->getPort(), $timeout);
        $this->setPath($this->credentials->getFolder());
        define('NET_SFTP_LOGGING', SFTP::LOG_REALTIME_FILE);
    }

    public function close()
    {
        $this->connection->_close_handle();
    }

    public function getFile($path, $fileName)
    {
        if (!$this->connection->get($fileName, $path . $fileName)) {
            throw new \Exception(sprintf('Failed to download file %s.', $fileName));
        }
    }

    public function listFiles()
    {
        if (!$data = $this->connection->nlist()) {
            throw new \Exception('Unable to list files');
        }

        $files = [];
        foreach ($data as $file) {
            if (!in_array($file, ['.', '..'])) {
                $files[] = $file;
            }
        }

        return $files;
    }

    public function putFile()
    {
        if (!$this->connection->put($this->tempFileName, $this->path . $this->fileName, SFTP::SOURCE_LOCAL_FILE)) {
            throw new \Exception(sprintf('Failed to upload file %s.', $this->tempFileName));
        }
    }

    public function deleteFile($fileName)
    {
        if (!$this->connection->delete($fileName)) {
            throw new \Exception(sprintf('Failed to delete %s on remote server.', $fileName));
        }
    }

    public function moveFile($fileName, $remote_file)
    {
        if (!$this->connection->rename($fileName, $remote_file)) {
            throw new \Exception(sprintf('Failed to move %s to %s on remote server.', $fileName, $remote_file));
        }
    }

    public function renameFile()
    {
        if (!$this->connection->rename($this->tempFileName, $this->fileName)) {
            throw new \Exception('Failed to rename file on remote server');
        }
    }

    public function verifyFile()
    {
        if (!$data = $this->connection->stat($this->fileName)) {
            throw new \Exception('Unable to get remote file size');
        }

        $size = [
            'remote' => $data['size'],
            'local' => filesize($this->path . $this->fileName)
        ];

        if ($size['remote'] != $size['local']) {
            throw new \Exception('Files are not of equal size');
        }
    }

    public function createRsaKey()
    {
        return new RSA();
    }

    private function getKey()
    {
        $key = $this->credentials->getPassword();

        if ($this->credentials->getKey()) {
            if (file_exists($this->credentials->getKey())) {
                $key = $this->createRsaKey();
                $key->loadKey(file_get_contents($this->credentials->getKey()));
            }
        }

        return $key;
    }

    public function login()
    {
        define('NET_SFTP_LOGGING', SFTP::LOG_REALTIME_FILE);
        if (!$this->connection->login($this->credentials->getUsername(), $this->getKey())) {
            throw new \Exception(sprintf(
                'Failed to connect to host %s.', $this->credentials->getHost()
            ));
        }
    }

    public function changeRemoteFolder()
    {
        if (!$this->connection->chdir($this->getPath())) {
            throw new \Exception(sprintf(
                'Unable to change directory to %s.',
                $this->getPath()
            ));
        }
        return;
    }
}