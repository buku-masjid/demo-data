<?php

namespace BukuMasjid\DemoData\Lecturings;

trait RandomLecturerNames
{
    public function randomLecturer(): string
    {
        $lecturerNames = [
            'Ustadz Fulan, S. Ag, M. Ag.',
            'Ustadz DR. H. Fulan, Lc, MH',
            'H. Fulan, Lc',
            'Prof. DR. H. Fulan, M. Pd.',
            'H. M. Fulan, SH. M. Sc.',
        ];

        return $lecturerNames[array_rand($lecturerNames)];
    }
}
