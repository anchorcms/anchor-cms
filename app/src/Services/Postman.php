<?php

namespace Anchorcms\Services;

class Postman
{

    protected $eol = "\r\n";

    protected function compileHeaders(array $headers)
    {
        $list = [];

        foreach ($headers as $key => $value) {
            $list[] = $key . ': ' . $value;
        }

        return implode($this->eol, $list) . $this->eol;
    }

    protected function formatAddress(array $recipient)
    {
        return sprintf('%s <%s>', current($recipient), key($recipient));
    }

    public function deliver(array $to, array $from, $subject, $body)
    {
        $headers = [
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=utf-8',
        ];

        $headers['To'] = $this->formatAddress($to);
        $headers['From'] = $this->formatAddress($from);

        $headerStr = $this->compileHeaders($headers);

        return mail(key($to), $subject, $body, $headerStr);
    }
}
