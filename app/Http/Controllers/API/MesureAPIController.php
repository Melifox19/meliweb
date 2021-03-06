<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Mesure;
use App\Models\Alerte;
use App\Models\Meliborne;
use App\Models\Ruche;
use App\Models\Rucher;
use App\Models\User;

use App\Notifications\AlerteMail;

class MesureAPIController extends AppBaseController
{
  public function index()
  {
    return Mesure::all();
  }

  public function store(Request $request)
  {
    // on suppose que la requête a été formulée correctement en JSON
    $data = $request->toArray();












    switch ($data['typ'])
    {
      case '0': //Envoie de données Méliruches --------------------------------------------------------
      $idSigfox = $data['idSigfox'];

      // On cherche la Meliborne correspondante à l'ID Sigfox
      $meliborne = Meliborne::where('idSigfox', $idSigfox)->first();
      $rucher = Rucher::where('id', $meliborne->idRucher)->first();
      $user = $rucher->users;

      if (isset($meliborne)) // Si on trouve une Meliborne
      {
        // On recherche la ruche correspondante à l'addresse Melinet de la Meliborne trouvé
        $ruche = Ruche::where('addrMelinet', $data['addrMelinet'])->where('idMeliborne', $meliborne->id)->first();

        if(isset($ruche)) // Si on trouve une Ruche
        {
          // On créé une entrée dans la table de mesure avec les mesures reçues
          $mesure = Mesure::create([
            'horodatageMesure' => date("Y-m-d H:i:s", $data['horodatageMesure']),
            'masse' => $data['masse'] / 20.47,
            'temperatureInt' => ($data['temperatureInt'] / 3.6286) -20,
            'temperatureExt' => ($data['temperatureExt'] / 3.6286) -20,
            'humiditeInt' => ($data['humiditeInt'] / 0.4286) +20,
            'pression' => ($data['pression'] / 1.02) +600,
            'niveauBatterie' => ($data['niveauBatterie']/ 0.06),
            'idRuche' => $ruche->id
          ]);

          // On vérifie si les valeurs ne sont pas trop critiques pour pouvoir créer l'alerte
          if ($mesure->masse > 70)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Masse elevee',
              'idRuche' => $mesure->idRuche
            ]);


            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }
          if ($mesure->masse < 20)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Masse faible',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($idAlerte));


          }
          if ($mesure->masse <= 0)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'vol',
              'description' => 'Ruche non detectee',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }

          // ------------ Température intérieure ---------------

          if ($mesure->temperatureInt > 36)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Temperature interieure elevee',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }
          if ($mesure->temperatureInt < 30)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Temperature interieure faible',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }

          // --------- Température extérieure --------

          if ($mesure->temperatureExt > 40)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Temperature exterieure elevee',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }
          if ($mesure->temperatureExt < 0)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Temperature exterieure faible',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }

          // ----------------- Humidité intérieure -----------------

          if ($mesure->humiditeInt > 25)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Humidite interieure elevee',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }
          if ($mesure->humiditeInt < 20)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Humidite interieure faible',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }

          // ----------- Niveau de batterie --------

          if ($mesure->niveauBatterie < 30)
          {
            $alerte = Alerte::create([
              'horodatageAlerte' => date("Y-m-d H:i:s"),
              'type' => 'mesure',
              'description' => 'Batterie faible',
              'idRuche' => $mesure->idRuche
            ]);

            $alerte = [ 'id' => $alerte->id ];

            $user->notify(new AlerteMail($alerte));

          }

          // on retourne l'article créé et un code réponse 201 (created)
          return response()->json($mesure, 201);
        }
      }
      break;











      case '1': //Envoie de données Mélilabos ---------------------------------------------------------------

      $idSigfox = $data['idSigfox'];

      // On recherhce la Melilabo correspondante à l'ID Sigfox
      $ruche = Ruche::where('idSigfox', $idSigfox)->first();

      if (isset($ruche)) // Si on trouve une Melilabo
      {
        // On créé alors une entrée dans la table Mesure avec les données reçues
        $mesure = Mesure::create([
          'horodatageMesure' => date("Y-m-d H:i:s", $data['horodatageMesure']),
          'masse' => $data['masse'],
          'temperatureInt' => $data['temperatureInt']-20,
          'temperatureExt' => $data['temperatureExt']-20,
          'humiditeInt' => $data['humiditeInt']+20,
          'humiditeExt' => $data['humiditeExt']+20,
          'pression' => $data['pression']+600,
          'niveauBatterie' => $data['niveauBatterie']*10,
          'debitSonore200' => $data['debitSonore200'],
          'debitSonore400' => $data['debitSonore400'],
          'idRuche' => $ruche->id
        ]);

        // On vérifie si les valeurs ne sont pas trop critiques pour pouvoir créer l'alerte
        if ($mesure->masse > 70)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Masse elevee',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }
        if ($mesure->masse < 20)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Masse faible',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));


        }
        if ($mesure->masse < 0)
        {
          Alert::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'vol',
            'description' => 'Ruche non detectee',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        // ------------ Température intérieure -------------------

        if ($mesure->temperatureInt > 36)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Temperature interieure elevee',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }
        if ($mesure->temperatureInt < 30)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Temperature interieure faible',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        // ------------- Température extérieure ---------------

        if ($mesure->temperatureExt > 40)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Temperature exterieure elevee',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }
        if ($mesure->temperatureExt < 0)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Temperature exterieure faible',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        // ------------- Humidité intérieure ---------------------

        if ($mesure->humiditeInt > 25)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Humidite interieure elevee',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }
        if ($mesure->humiditeInt < 20)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Humidite interieure faible',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        // ------------- Humidité extérieure ------------------

        if ($mesure->humiditeExt > 25)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Humidite exterieure elevee',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }
        if ($mesure->humiditeExt < 20)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Humidite exterieure faible',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        // -------------- Niveau de batterie -------------

        if ($mesure->niveauBatterie < 30)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Batterie faible',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        // ------------ Débit Sonore (200Hz & 400 Hz) --------------

        if ($mesure->debitSonore200 > 190 && $mesure->debitSonore200 < 210)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Essaimage potentiel (200 Hz)',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        if ($mesure->debitSonore400 > 400 && $mesure->debitSonore400 < 500)
        {
          $alerte = Alerte::create([
            'horodatageAlerte' => date("Y-m-d H:i:s"),
            'type' => 'mesure',
            'description' => 'Essaimage potentiel (400 Hz)',
            'idRuche' => $mesure->idRuche
          ]);

          $alerte = [ 'id' => $alerte->id ];

          $user->notify(new AlerteMail($alerte));

        }

        // on retourne l'article créé et un code réponse 201 (created)
        //return response()->json($mesure, 201);
      }
      break;













      case '2': //Envoie de données de géolocalisation ----------------------------------------------------------
      $idSigfox = $data['idSigfox'];

      $ruche = Ruche::where('idSigfox', $idSigfox)->first();
      if (isset($ruche)) // Si non, on cherche si une mélilabo correspondante
      {
        $ruche_insert = [
          'longitude' => $data['longitude'],
          'latitude' => $data['latitude']
        ];

        // On modifie la géolocalisation de la Melilabo
        $ruche_rslt = Mesure::where('id', $ruche->id)->update($ruche_insert);

        // on retourne l'article créé et un code réponse 201 (created)
        //return response()->json($ruche_rslt, 201);
      }
      break;









      case '11': //Non attribué... ------------------------------------------------------------------------------
      // code...
      break;

      default:
      // code...
      break;
    }
  }
}
