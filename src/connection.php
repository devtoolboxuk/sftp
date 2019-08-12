<?php

namespace devtoolbox\sftplib;

interface connection
{

    public function setPath($path);

    public function getPath();

    public function setFileName($fileName);

    public function getFileName();

    public function getTempFilename();

    public function create(Credentials $credentials, $timeout = 10);

    public function login();

    public function putFile();
    public function listFiles();

    public function getFile($path, $fileName);

    public function deleteFile($fileName);

    public function renameFile();

    public function moveFile($fileName, $remote_file);

    public function verifyFile();
    public function changeRemoteFolder();


}