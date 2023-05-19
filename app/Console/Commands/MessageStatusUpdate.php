<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MessageStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:message ';

    /**
     * The console command description.
     *
     * @var string 
     */
    protected $description = 'Messaging status update  '; 
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        //created and modified by ashok 06thapril2021
        

        messageResponse();
        
        
    }
}
