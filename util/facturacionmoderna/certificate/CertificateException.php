<?php

namespace util\facturacionmoderna\certificate;

class CertificateException extends \Exception {
    const FILE_NOT_READABLE = 101;
    const WITHOUT_CERT = 102;
    const NOT_ABLE_TO_SIGN_STRING = 103;
}