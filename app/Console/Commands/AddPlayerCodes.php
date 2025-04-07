<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;

class AddPlayerCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-player-codes';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'Adds unique random codes to all players';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $players = Player::all();
        $usedCodes = [];
        
        foreach ($players as $player) {
            do {
                $code = $this->generateRandomCode();
            } while (in_array($code, $usedCodes));
            
            $usedCodes[] = $code;
            $player->code = $code;
            $player->save();
        }
        
        $this->info('Códigos generados y asignados exitosamente a '. count($players) . ' jugadores');
    }

    private function generateRandomCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < 10; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $code;
    }
}
