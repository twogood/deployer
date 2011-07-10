<?php
namespace services;

class SecureShell
{
  private static function getUsername($host)
  {
    if (!isset($host->ssh_username))
      return 'deploy';
    else
      return $host->ssh_username;
  }

  public function login($host)
  {
    $ssh = @ssh2_connect($host->name);
    if ($ssh === false)
      throw new \InvalidArgumentException("Could not connect to host: ".$host->name);
    $fingerprint = ssh2_fingerprint($ssh);
    if ($host->ssh_fingerprint != $fingerprint)
      throw new \Exception("Fingerprint mismatch, received " . $fingerprint);

    $success = @ssh2_auth_pubkey_file($ssh, 
      self::getUsername($host), 
      $host->ssh_pubkeyfile, 
      $host->ssh_privkeyfile);

    if ($success !== true)
    {
      throw new \models\LastErrorException();
    }

    return $ssh;
  }

  public function runCommand($host, $command)
  {
    $ssh = $this->login($host);

    $stream = ssh2_exec($ssh, $command);
    stream_set_blocking($stream, true);

    $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
    stream_set_blocking($errorStream, true);

    $output = stream_get_contents($stream);
    $error = stream_get_contents($errorStream);
/*
echo "Output: [" . ."]";
echo "Error: [" . ."]";
 */
    unset($ssh);

    $stderr = fopen("php://stderr", 'w');
    fwrite($stderr, "runCommand('".$host->name."', '$command') output:\n$output\n");
    fwrite($stderr, "runCommand('".$host->name."', '$command') error:\n$error\n");
    fclose($stderr);

    return array(trim($output), trim($error));
  }

  public function uploadFileData($host, $remoteFile, $fileData)
  {
    $localFile = tempnam('/tmp', 'uploadFileData');
    //echo "\n[$localFile]\n";
    file_put_contents($localFile, $fileData);

    $ssh = $this->login($host);
    $success = ssh2_scp_send($ssh, $localFile, $remoteFile);
    unset($ssh);
    if ($success == false)
      throw new \Exception("Failed to send $localFile to $remoteFile");
  }
}
