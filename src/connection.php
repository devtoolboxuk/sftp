<?php

namespace devtoolbox\sftplib;

interface connection
{
    public function createClient(Credentials $credentials, $timeout = 10);

    public function login();

    public function putFile();

    public function getFile($path, $fileName);

    public function deleteFile($fileName);

    public function renameFile();

    public function moveFile($fileName, $remote_file);

    public function verify();

}