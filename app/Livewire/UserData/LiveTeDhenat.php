<?php

namespace App\Livewire\UserData;

use App\Models\Human\UserData;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LiveTeDhenat extends Component
{
    public $modalMatjeHapur = false;
    public $pesha;
    public $gjatesia;
    public $data;
    public $njesiaPeshes = 'kg'; // Vlera default
    public $njesiaGjatesise = 'cm'; // Vlera default

// 2. Funksioni për hapjen e modalit
    public $stervitjaPezull = null; // Nëse e ke nga kodi i mësipërm

    public function hapModalMatje(): void
    {
        $this->pesha = '';
        $this->gjatesia = '';
        $this->data = now()->toDateString(); // Vendos automatikisht datën e sotme
        $this->njesiaPeshes = 'kg';
        $this->njesiaGjatesise = 'cm';

        $this->modalMatjeHapur = true;
    }

// 3. Funksioni për mbylljen e modalit
    public function mbyllModalMatje(): void
    {
        $this->modalMatjeHapur = false;
    }

// 4. Funksioni i ruajtjes së të dhënave
    public function ruajMatjet(): void
    {
        // Validimi i rreptë që pranon vetëm numra dhe njësitë e sakta (e sigurt për SQLite)
        $this->validate([
            'data'             => 'required|date',
            'pesha'            => 'required|numeric|min:1',
            'gjatesia'         => 'required|numeric|min:1',
            'njesiaPeshes'     => 'required|in:kg,lbs',
            'njesiaGjatesise'  => 'required|in:cm,in',
        ]);

        try {
            // Ruajtja në tabelën 'user_data'
            UserData::create([
                'user_id'          => Auth::id(),
                'pesha'            => $this->pesha,
                'gjatesia'         => $this->gjatesia,
                'data'             => $this->data,
                'njesia_peshes'    => $this->njesiaPeshes,
                'njesia_gjatesise' => $this->njesiaGjatesise,
            ]);

            // Mbyllim modalin pas suksesit
            $this->mbyllModalMatje();

            // Shfaqim njoftimin SweetAlert
            $this->dispatch('showSweetAlert', [
                'type'    => 'success',
                'title'   => 'U shtua!',
                'message' => 'Matjet fizike u regjistruan me sukses.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', [
                'type'    => 'error',
                'title'   => 'Gabim!',
                'message' => 'Diçka shkoi gabim gjatë ruajtjes së matjeve.',
            ]);
        }
    }

    public function getHistorikuMatjeveProperty()
    {
        // Marrim të gjitha matjet e përdoruesit (p.sh. 100 të fundit)
        $matjet = UserData::where('user_id', auth()->id())
            ->latest('data')
            ->get();

        return $matjet->map(function ($matja) {
            $pesha = $matja->pesha;
            $gjatesia = $matja->gjatesia;
            $bmi = 0;

            // Algoritmi i përllogaritjes së BMI-së
            if ($pesha > 0 && $gjatesia > 0) {
                if ($matja->njesia_peshes === 'kg' && $matja->njesia_gjatesise === 'cm') {
                    $gjatesiaMetra = $gjatesia / 100;
                    $bmi = $pesha / ($gjatesiaMetra * $gjatesiaMetra);
                } else {
                    $peshaLbs = $matja->njesia_peshes === 'kg' ? $pesha * 2.20462 : $pesha;
                    $gjatesiaInches = $matja->njesia_gjatesise === 'cm' ? $gjatesia * 0.393701 : $gjatesia;
                    $bmi = ($peshaLbs / ($gjatesiaInches * $gjatesiaInches)) * 703;
                }
            }

            // Përcaktimi i klasës së ngjyrës dhe statusit bazuar te BMI
            if ($bmi < 18.5) {
                $statusi = 'Nënpeshë';
                $klasaNgjyres = 'warning'; // E verdhë
                $bgKopes = '#FFF9E6';
            } elseif ($bmi >= 18.5 && $bmi < 25) {
                $statusi = 'Normal';
                $klasaNgjyres = 'success'; // E gjelbër
                $bgKopes = '#F4FBF7';
            } elseif ($bmi >= 25 && $bmi < 30) {
                $statusi = 'Mbipeshë';
                $klasaNgjyres = 'warning'; // E verdhë
                $bgKopes = '#FFF9E6';
            } else {
                $statusi = 'Obezitet';
                $klasaNgjyres = 'danger'; // E kuqe
                $bgKopes = '#FDF2F4';
            }

            return [
                'id' => $matja->id,
                'data' => \Carbon\Carbon::parse($matja->data)->format('d M Y'),
                'pesha' => $pesha . ' ' . $matja->njesia_peshes,
                'gjatesia' => $gjatesia . ' ' . $matja->njesia_gjatesise,
                'bmi' => number_format($bmi, 1),
                'statusi' => $statusi,
                'klasa_ngjyres' => $klasaNgjyres,
                'bg_kutise' => $bgKopes
            ];
        });
    }

// Funksion i thjeshtë nëse dëshiron të bësh diçka kur klikohet kutia (p.sh. për editim)
    public function ShfaqDetajetMatjes($id)
    {
        // Logjika nëse do të hapësh një modal tjetër për editim ose fshirje në të ardhmen
    }

    public function render()
    {
        return view('livewire.user-data.live-te-dhenat')->layout('layouts.dashboard.app');
    }
}
