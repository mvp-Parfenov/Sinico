<?php

namespace App\Console\Commands\Advert;

use App\Entity\Adverts\Advert\Advert;
use App\UseCases\Adverts\AdvertService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advert:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'expire command';

    /**
     * @var AdvertService
     */
    private $service;

    /**
     * Create a new command instance.
     *
     * @param AdvertService $service
     */
    public function __construct(AdvertService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): bool
    {
        $success = true;

        foreach (
            Advert::active()
                ->where(
                    'expired_at',
                    '<',
                    Carbon::now())->cursor() as $advert) {
            try{
                $this->service->expire($advert);
            }catch (\DomainException $e){
                $this->error($e->getMessage());
                $success = false;
            }

        }

        return $success;
    }
}
