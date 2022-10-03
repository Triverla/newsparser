<?php

namespace App\Interfaces;

interface ResourceInterface
{
    public function getUrl(): string;
    public function getName(): string;
    public function getWrapperSelector(): string;
    public function getTitleSelector(): string;
    public function getDescSelector(): string;
    public function getDateSelector(): string;
    public function getImageSelector(): string;
}