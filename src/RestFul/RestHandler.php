<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\RestFul;

class RestHandler extends RestResponse
{

    /** @var string */
    protected string $contentType;
    /** @var array */
    protected array $data = [];
    /** @var string */
    protected const DEFAULT_CONTENT = 'json';
    /** @var int */
    protected const CODE_404 = 404;
    /** @var int */
    protected const CODE_500 = 500;
    /** @var int */
    protected const CODE_200 = 200;

    /**
     * Return various response including json response for RestFul API call
     *
     * @param array $data
     * @param int $code - defaults to 200
     * @param string|null $type
     * @return mixed
     */
    public function response(array $data, int $code = self::CODE_200, string|null $type = self::DEFAULT_CONTENT): mixed
    {
        //$code = $code;
        if (empty($data)) {
            $data = ['error' => 'No data found!'];
        }
        $this->setHttpHeaders($type, $code);
        if (str_contains($type, 'json')) {
            $response = $this->jsonEncodedData($data);
        } elseif (str_contains($type, 'html')) {
            $response = $this->htmlEncodedData($data);
        } elseif (str_contains($type, 'xml')) {
            $response = $this->xmlEncodedData($data);
        } else {
            $response = $data;
        }

        return $response;
    }

    /**
     * Return a json encoded response as a string
     *
     * @param array $data
     * @return string
     */
    private function jsonEncodedData(array $data): string
    {
        if ($data !== null) {
            $json = json_encode($data);
            if ($json) {
                return $json;
            }
        }
    }

    public function htmlEncodedData(array $data): ?string
    {
        if ($data !==null) {
            $html = "<table border=\"1\">";
            if (count($data) > 0) {
                foreach ($data as $key => $value) {
                    $html .= "<tr>";
                    $html .= "<td>{$key}</td>";
                    $html .= "<td>{$value}</td>";
                    $html .= "</tr>";
                }
            }
            $html .= "</table>";

            return $html;
        }

        return null;
    }

    public function xmlEncodedData(array $data): bool|string|null
    {
        if ($data !==null) {
            $xml = new \SimpleXMLElement('<?xml version="1.0"?><mobile></mobile>');
            foreach ($data as $key => $value) {
                $xml->addChild($key, $value);
            }
            return $xml->asXML();
        }

        return null;
    }
}
