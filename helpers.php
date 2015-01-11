<?php

if (!function_exists('gzdecode')) {
    /**
     * gzdecode function
     */
    function gzdecode($data)
    {
        do {
            $tempName = uniqid('temp ');
        } while (file_exists($tempName));

        if (file_put_contents($tempName, $data)) {
            try {
                ob_start();
                @readgzfile($tempName);
                $uncompressed = ob_get_clean();
            } catch (Exception $e) {
                $ex = $e;
            }

            unlink($tempName);

            if (isset($ex)) {
                throw $ex;
            }

            return $uncompressed;
        }
    }
}
