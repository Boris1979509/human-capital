<?php

namespace App\Models\Traits;

trait InteractsWithPeriods
{
    private function getYearWord(int $year)
    {
        return $this->num2str($year, ['год', 'года', 'лет']);
    }

    private function getMonthWord(int $month)
    {
        return $this->num2str($month, ['месяц', 'месяца', 'месяцев']);
    }

    /**
     * http://jsfiddle.net/dizzy2/XXvtZ/
     *
     * @param  integer количество
     * @param  array варианты склонений [для одного, для двух-четырёх, больше четырёх]
     * @returns string
     */
    private function num2str($n, $text_forms)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;

        if ($n > 10 && $n < 20) {
            return $text_forms[2];
        }

        if ($n1 > 1 && $n1 < 5) {
            return $text_forms[1];
        }

        if ($n1 === 1) {
            return $text_forms[0];
        }
        return $text_forms[2];
    }


    private function getMonth($month): string
    {
        $months = [
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь',
        ];

        return $months[$month - 1] ?? '';
    }
}
