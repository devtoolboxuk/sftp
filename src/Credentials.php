<?php

namespace devtoolbox\sftplib;

class Credentials
{

    /**
     * The host name.
     * @var string
     */
    protected $host = '';

    /**
     * The port number.
     * @var integer
     */
    protected $port = 22;

    /**
     * The user name.
     * @var string
     */
    protected $username = '';

    /**
     * The password.
     * @var string
     */
    protected $password = '';

    /**
     * The default folder to change to.
     * @var string
     */
    protected $folder = '/';
    protected $key = null;
    protected $type = 'sftp';
    protected $uploadMode = 'FTP_ASCII';
    protected $downloadMode = 'FTP_ASCII';

    /**
     * Set credential values.
     * @param array $options The various details for the connection
     */
    public function __construct($options)
    {
        $this->host = isset($options['host']) ? $options['host'] : $this->host;
        $this->type = isset($options['type']) ? $options['type'] : $this->type;
        $this->port = isset($options['port']) ? $options['port'] : $this->port;
        $this->username = isset($options['username']) ? $options['username'] : $this->username;
        $this->password = isset($options['password']) ? $options['password'] : $this->password;
        $this->folder = isset($options['folder']) ? $options['folder'] : '/';
        $this->key = isset($options['key']) ? $options['key'] : $this->key;
        $this->uploadMode = isset($options['upload_mode']) ? $options['upload_mode'] : $this->uploadMode;
        $this->downloadMode = isset($options['download_mode']) ? $options['download_mode'] : $this->downloadMode;
    }

    /**
     * Get the host name.
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    public function getDownloadMode()
    {
        return $this->downloadMode;
    }

    public function getUploadMode()
    {
        return $this->uploadMode;
    }

    public function getKey()
    {
        return $this->key;
    }


    public function getType()
    {
        return $this->type;
    }


    /**
     * Get the port number.
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Get the user name.
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the password.
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the default folder.
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
