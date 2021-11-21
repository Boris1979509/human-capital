<?php


namespace App\Models\Resume;

use App\Models\Traits\InteractsWithPeriods;
use App\Models\UserPersonal\UserJob;
use Carbon\Carbon;
use DateInterval;

class ResumeJob
{
    use InteractsWithPeriods;

    protected UserJob $job;

    public function __construct(UserJob $job)
    {
        $this->job = $job;
    }


    public function getPeriod(): string
    {
        $from = "{$this->getMonth($this->job->month_begin)} {$this->job->year_begin}";
        $to = $this->job->until_now ? 'н.в.' : "{$this->getMonth($this->job->month_end)} {$this->job->year_end}";

        return "$from - $to";
    }

    public function getPeriodDuration(): string
    {
        $diff = $this->getPeriodDurationValue();

        $result = '';
        if ($diff->y > 0) {
            $result .= "$diff->y {$this->getYearWord($diff->y)}";
        }
        if ($diff->y > 0 && $diff->m) {
            $result .= ", ";
        }
        if ($diff->m > 0) {
            $result .= "$diff->m {$this->getMonthWord($diff->m)}";
        }
        return $result;
    }

    public function getPeriodDurationValue(): DateInterval
    {
        $from = Carbon::create($this->job->year_begin, $this->job->month_begin);
        $to = $this->job->until_now ? now() : Carbon::create($this->job->year_end, $this->job->month_end);

        return $to->diff($from);
    }

    public function getCompany(): ?string
    {
        return $this->job->company;
    }

    public function getCompanyWebsite(): ?string
    {
        return $this->job->website;
    }

    public function getPosition(): ?string
    {
        return $this->job->position;
    }

    public function getDescription(): ?string
    {
        return $this->job->description;
    }

    public function toArray(): array
    {
        return [
            'period' => $this->getPeriod(),
            'period_duration' => $this->getPeriodDuration(),
            'company' => $this->getCompany(),
            'company_website' => $this->getCompanyWebsite(),
            'position' => $this->getPosition(),
            'description' => $this->getDescription(),
        ];
    }
}
