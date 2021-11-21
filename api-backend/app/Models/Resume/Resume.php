<?php

namespace App\Models\Resume;

use App\Models\Traits\InteractsWithPeriods;
use App\Models\User;
use Carbon\CarbonInterval;

class Resume
{
    use InteractsWithPeriods;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getName(): string
    {
        return optional($this->user->personal)->first_name.' '.optional($this->user->personal)->last_name;
    }

    public function getAge()
    {
        return optional(optional($this->user->personal)->birthday)->diffInYears(now());
    }

    public function getCity()
    {
        return optional(optional($this->user->personal)->city)->name;
    }

    public function getPhone(): ?string
    {
        return $this->user->phone;
    }

    public function getMail(): string
    {
        return $this->user->email;
    }

    public function getTotalExperience(): string
    {
        $totalInterval = $this->getJobs()
            ->reduce(function (CarbonInterval $result, ResumeJob $job) {
                return $result->add($job->getPeriodDurationValue());
            }, new CarbonInterval(0, 0));
        return "$totalInterval->y {$this->getYearWord($totalInterval->y)}, $totalInterval->m {$this->getMonthWord($totalInterval->m)}";
    }

    public function getJobs()
    {
        return $this->user->jobs()
            ->orderByDesc('year_begin')
            ->orderByDesc('month_begin')
            ->get()
            ->map(fn ($job) => new ResumeJob($job));
    }

    public function getEducation()
    {
        return $this->user->education()
            ->orderByDesc('year_begin')
            ->with(['university', 'quality'])
            ->get()
            ->map(fn ($education) => new ResumeEducation($education));
    }

    public function getSkills(): string
    {
        return optional($this->user->personal)->skills ? implode(', ', $this->user->personal->skills) : "";
    }

    public function getDescription(): string
    {
        return optional($this->user->personal)->description ?? '';
    }

    public function getAvatarUrl(): string
    {
        return optional($this->user->getFirstMedia('avatar'))->getFullUrl() ?? '';
    }

    public function toArray(): array
    {
        return [
            'avatar' => $this->getAvatarUrl(),
            'name' => $this->getName(),
            'age' => $this->getAge(),
            'city' => $this->getCity(),
            'phone' => $this->getPhone(),
            'mail' => $this->getMail(),
            'total_experience' => $this->getTotalExperience(),
            'job' => $this->getJobsArray(),
            'education' => $this->getEducationsArray(),
            'skills' => $this->getSkills(),
            'description' => $this->getDescription()
        ];
    }

    private function getJobsArray(): array
    {
        $jobs = [];
        foreach ($this->getJobs() as $job) {
            /** @var ResumeJob $job */
            $jobs[] = $job->toArray();
        }
        return $jobs;
    }

    public function getEducationsArray(): array
    {
        $educations = [];
        foreach ($this->getEducation() as $education) {
            /** @var ResumeEducation $education */
            $educations[] = $education->toArray();
        }
        return $educations;
    }
}
