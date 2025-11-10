<?php

namespace Riches\MenuConsumer\Auth;

interface TokenProvider
{
    public function getToken(): string;
}
