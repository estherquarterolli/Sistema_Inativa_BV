<?php

namespace App\Models;

class Student
{
    public function __construct(
        public readonly string $nomeOriginal,
        public readonly string $nomeNormalizado,
        public readonly string $id
    ) {
    }
}
