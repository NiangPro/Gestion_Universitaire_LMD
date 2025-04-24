<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class Outils extends Model
{
    use HasFactory;

    public function anneeAcademique()
    {
        return AcademicYear::where("campus_id", Auth::user()->campus_id)->where("encours", true)->first() ?: null;
    }
    public function addHistorique($description, $type)
    {
        $agent = new Agent();

        $navigateur = $agent->browser();
        $version = $agent->version($navigateur);
        $os = $agent->platform();

        Historique::create([
            'user_id' => Auth::user()->id,
            'type' => $type,
            'description' => $description,
            'device' => $agent->isPhone() ? 'Téléphone' : ($agent->isTablet() ? 'Tablette' : 'Ordinateur'),
            'ip' => request()->ip(),
            'navigateur' => "$navigateur $version ($os)",
            'campus_id' => Auth::user()->estSuperAdmin() ? null : Auth::user()->campus_id
        ]);
    }

    public function createSuperAdmin()
    {
        $user = User::where("role", "superadmin")->first();
        if (!$user) {
            User::create([
                "prenom" => "Super",
                "nom" => "Admin",
                "role" => "superadmin",
                "username" => "superadmin",
                "sexe" => "homme",
                "email" => "superadmin@gmail.com",
                "tel" => "777457575",
                "image" => "profil.jpg",
                "adresse" => "PA U17",
                "password" => '$2y$12$t89ESRTMVlScrILmxeD0NuAZcGYMRdIZ2.xCFXe60fw4vBwhshjT6',
            ]);
        }

        $se = Semaine::get();

        if (count($se) == 0) {
            $tab = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
            foreach ($tab as $val) {
                Semaine::create(["jour" => $val]);
            }
        }
    }

    public function isLogged()
    {
        if (!Auth::user()) {
            redirect(route("login"));
        }
    }

    public function initActivation()
    {

        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.' . config('database.default') . '.database');
        $nom = "Tables_in_" . $databaseName;

        foreach ($tables as $t) {
            if (!in_array($t->$nom, ["historiques", "activations", "messages", "cache", "cache_locks", "failed_jobs", "job_batches", "jobs", "migrations", "password_reset_tokens", "sessions"])) {
                $act = Activation::where("nom", $t->$nom)->first();

                $trouve = strpos($t->$nom, "_");

                if ($trouve) {
                    $left = ucfirst(substr($t->$nom, 0, $trouve));
                    $right = ucfirst(substr($t->$nom, $trouve + 1, strlen($t->$nom) - ($trouve + 2)));
                    $model = $left . $right;
                } else {
                    $model = ucfirst(substr($t->$nom, 0, strlen($t->$nom) - 1));
                }

                if (!$act) {
                    Activation::create([
                        "nom" => $t->$nom,
                        "model" => $model == "Campuse" ? "Campus" : $model
                    ]);
                }
            }
        }
    }



    public function searchUser($value)
    {
        $value = '%' . $value . '%'; // Assurez-vous que le format est prêt pour la requête LIKE
        $users = [];
        if (Auth::user()->estSuperAdmin()) {
            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where(function ($query) {
                    $query->where('role', 'admin')
                        ->orWhere('role', 'superadmin');
                })
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        } elseif (Auth::user()->estAdmin()) {

            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where(function ($query) {
                    $query->where('campus_id', Auth::user()->campus_id)
                        ->orWhere('role', 'superadmin');
                })
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        } elseif (Auth::user()->estParent()) {
            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where('campus_id', Auth::user()->campus_id)
                ->where('role', 'admin')
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        } else {
            $users = User::where(function ($query) use ($value) {
                $query->where('prenom', 'LIKE', $value)
                    ->orWhere('email', 'LIKE', $value);
            })
                ->where('campus_id', Auth::user()->campus_id)
                ->where('id', '!=', Auth::id()) // Exclure l'utilisateur connecté
                ->get();
        }

        return $users;
    }

    public function initCountries()
    {
        $countPays = Country::count();
        if($countPays === 0){
            $pays = [
                ['AF', 'AFG', 'Afghanistan', 'Afghanistan'],
                ['AL', 'ALB', 'Albania', 'Albanie'],
                ['AQ', 'ATA', 'Antarctica', 'Antarctique'],
                ['DZ', 'DZA', 'Algeria', 'Algérie'],
                ['AS', 'ASM', 'American Samoa', 'Samoa Américaines'],
                ['AD', "AND", 'Andorra', 'Andorre'],
                ['AO', 'AGO', 'Angola', 'Angola'],
                ['AG', 'ATG', 'Antigua and Barbuda', 'Antigua-et-Barbuda'],
                ['AZ', 'AZE', 'Azerbaijan', 'Azerbaïdjan'],
                [ 'AR', 'ARG', 'Argentina', 'Argentine'],
                [ 'AU', 'AUS', 'Australia', 'Australie'],
                [ 'AT', 'AUT', 'Austria', 'Autriche'],
                [ 'BS', 'BHS', 'Bahamas', 'Bahamas'],
                [ 'BH', 'BHR', 'Bahrain', 'Bahreïn'],
                [ 'BD', 'BGD', 'Bangladesh', 'Bangladesh'],
                [ 'AM', 'ARM', 'Armenia', 'Arménie'],
                [ 'BB', 'BRB', 'Barbados', 'Barbade'],
                [ 'BE', 'BEL', 'Belgium', 'Belgique'],
                [ 'BM', 'BMU', 'Bermuda', 'Bermudes'],
                [ 'BT', 'BTN', 'Bhutan', 'Bhoutan'],
                [ 'BO', 'BOL', 'Bolivia', 'Bolivie'],
                [ 'BA', 'BIH', 'Bosnia and Herzegovina', 'Bosnie-Herzégovine'],
                [ 'BW', 'BWA', 'Botswana', 'Botswana'],
                ['BV', 'BVT', 'Bouvet Island', 'Île Bouvet'],
                ['BR', 'BRA', 'Brazil', 'Brésil'],
                ['BZ', 'BLZ', 'Belize', 'Belize'],
                ['IO', 'IOT', 'British Indian Ocean Territory', 'Territoire Britannique de l\'Océan Indien'],
                ['SB', 'SLB', 'Solomon Islands', 'Îles Salomon'],
                ['VG', 'VGB', 'British Virgin Islands', 'Îles Vierges Britanniques'],
                ['BN', 'BRN', 'Brunei Darussalam', 'Brunéi Darussalam'],
                ['BG', 'BGR', 'Bulgaria', 'Bulgarie'],
                ['MM', 'MMR', 'Myanmar', 'Myanmar'],
                ['BI', 'BDI', 'Burundi', 'Burundi'],
                ['BY', 'BLR', 'Belarus', 'Bélarus'],
                ['KH', 'KHM', 'Cambodia', 'Cambodge'],
                ['CM', 'CMR', 'Cameroon', 'Cameroun'],
                ['CA', 'CAN', 'Canada', 'Canada'],
                ['CV', 'CPV', 'Cape Verde', 'Cap-vert'],
                ['KY', 'CYM', 'Cayman Islands', 'Îles Caïmanes'],
                ['CF', 'CAF', 'Central African', 'République Centrafricaine'],
                ['LK', 'LKA', 'Sri Lanka', 'Sri Lanka'],
                ['TD', 'TCD', 'Chad', 'Tchad'],
                ['CL', 'CHL', 'Chile', 'Chili'],
                ['CN', 'CHN', 'China', 'Chine'],
                ['TW', 'TWN', 'Taiwan', 'Taïwan'],
                ['CX', 'CXR', 'Christmas Island', 'Île Christmas'],
                ['CC', 'CCK', 'Cocos (Keeling) Islands', 'Îles Cocos (Keeling)'],
                ['CO', 'COL', 'Colombia', 'Colombie'],
                ['KM', 'COM', 'Comoros', 'Comores'],
                ['YT', 'MYT', 'Mayotte', 'Mayotte'],
                ['CG', 'COG', 'Republic of the Congo', 'République du Congo'],
                ['CD', 'COD', 'The Democratic Republic Of The Congo', 'République Démocratique du Congo'],
                ['CK', 'COK', 'Cook Islands', 'Îles Cook'],
                ['CR', 'CRI', 'Costa Rica', 'Costa Rica'],
                ['HR', 'HRV', 'Croatia', 'Croatie'],
                ['CU', 'CUB', 'Cuba', 'Cuba'],
                ['CY', 'CYP', 'Cyprus', 'Chypre'],
                ['CZ', 'CZE', 'Czech Republic', 'République Tchèque'],
                ['BJ', 'BEN', 'Benin', 'Bénin'],
                ['DK', 'DNK', 'Denmark', 'Danemark'],
                ['DM', 'DMA', 'Dominica', 'Dominique'],
                ['DO', 'DOM', 'Dominican Republic', 'République Dominicaine'],
                ['EC', 'ECU', 'Ecuador', 'Équateur'],
                ['SV', 'SLV', 'El Salvador', 'El Salvador'],
                ['GQ', 'GNQ', 'Equatorial Guinea', 'Guinée Équatoriale'],
                ['ET', 'ETH', 'Ethiopia', 'Éthiopie'],
                ['ER', 'ERI', 'Eritrea', 'Érythrée'],
                ['EE', 'EST', 'Estonia', 'Estonie'],
                ['FO', 'FRO', 'Faroe Islands', 'Îles Féroé'],
                ['FK', 'FLK', 'Falkland Islands', 'Îles (malvinas) Falkland'],
                ['GS', 'SGS', 'South Georgia and the South Sandwich Islands', 'Géorgie du Sud et les Îles Sandwich du Sud'],
                ['FJ', 'FJI', 'Fiji', 'Fidji'],
                ['FI', 'FIN', 'Finland', 'Finlande'],
                ['AX', 'ALA', 'Åland Islands', 'Îles Åland'],
                ['FR', 'FRA', 'France', 'France'],
                ['GF', 'GUF', 'French Guiana', 'Guyane Française'],
                ['PF', 'PYF', 'French Polynesia', 'Polynésie Française'],
                ['TF', 'ATF', 'French Southern Territories', 'Terres Australes Françaises'],
                ['DJ', 'DJI', 'Djibouti', 'Djibouti'],
                ['GA', 'GAB', 'Gabon', 'Gabon'],
                ['GE', 'GEO', 'Georgia', 'Géorgie'],
                ['GM', 'GMB', 'Gambia', 'Gambie'],
                ['PS', 'PSE', 'Occupied Palestinian Territory', 'Territoire Palestinien Occupé'],
                ['DE', 'DEU', 'Germany', 'Allemagne'],
                ['GH', 'GHA', 'Ghana', 'Ghana'],
                ['GI', 'GIB', 'Gibraltar', 'Gibraltar'],
                ['KI', 'KIR', 'Kiribati', 'Kiribati'],
                ['GR', 'GRC', 'Greece', 'Grèce'],
                ['GL', 'GRL', 'Greenland', 'Groenland'],
                ['GD', 'GRD', 'Grenada', 'Grenade'],
                ['GP', 'GLP', 'Guadeloupe', 'Guadeloupe'],
                ['GU', 'GUM', 'Guam', 'Guam'],
                ['GT', 'GTM', 'Guatemala', 'Guatemala'],
                ['GN', 'GIN', 'Guinea', 'Guinée'],
                ['GY', 'GUY', 'Guyana', 'Guyana'],
                ['HT', 'HTI', 'Haiti', 'Haïti'],
                ['HM', 'HMD', 'Heard Island and McDonald Islands', 'Îles Heard et Mcdonald'],
                ['VA', 'VAT', 'Vatican City State', 'Saint-Siège (état de la Cité du Vatican)'],
                ['HN', 'HND', 'Honduras', 'Honduras'],
                ['HK', 'HKG', 'Hong Kong', 'Hong-Kong'],
                ['HU', 'HUN', 'Hungary', 'Hongrie'],
                ['IS', 'ISL', 'Iceland', 'Islande'],
                ['IN', 'IND', 'India', 'Inde'],
                ['ID', 'IDN', 'Indonesia', 'Indonésie'],
                ['IR', 'IRN', 'Islamic Republic of Iran', 'République Islamique d\'Iran'],
                ['IQ', 'IRQ', 'Iraq', 'Iraq'],
                ['IE', 'IRL', 'Ireland', 'Irlande'],
                ['IL', 'ISR', 'Israel', 'Israël'],
                ['IT', 'ITA', 'Italy', 'Italie'],
                ['CI', 'CIV', 'Côte d\'Ivoire', 'Côte d\'Ivoire'],
                ['JM', 'JAM', 'Jamaica', 'Jamaïque'],
                ['JP', 'JPN', 'Japan', 'Japon'],
                ['KZ', 'KAZ', 'Kazakhstan', 'Kazakhstan'],
                ['JO', 'JOR', 'Jordan', 'Jordanie'],
                ['KE', 'KEN', 'Kenya', 'Kenya'],
                ['KP', 'PRK', 'Democratic People\'s Republic of Korea', 'République Populaire Démocratique de Corée'],
                ['KR', 'KOR', 'Republic of Korea', 'République de Corée'],
                ['KW', 'KWT', 'Kuwait', 'Koweït'],
                ['KG', 'KGZ', 'Kyrgyzstan', 'Kirghizistan'],
                ['LA', 'LAO', 'Lao People\'s Democratic Republic', 'République Démocratique Populaire Lao'],
                ['LB', 'LBN', 'Lebanon', 'Liban'],
                ['LS', 'LSO', 'Lesotho', 'Lesotho'],
                ['LV', 'LVA', 'Latvia', 'Lettonie'],
                ['LR', 'LBR', 'Liberia', 'Libéria'],
                ['LY', 'LBY', 'Libyan Arab Jamahiriya', 'Jamahiriya Arabe Libyenne'],
                ['LI', 'LIE', 'Liechtenstein', 'Liechtenstein'],
                ['LT', 'LTU', 'Lithuania', 'Lituanie'],
                ['LU', 'LUX', 'Luxembourg', 'Luxembourg'],
                ['MO', 'MAC', 'Macao', 'Macao'],
                ['MG', 'MDG', 'Madagascar', 'Madagascar'],
                ['MW', 'MWI', 'Malawi', 'Malawi'],
                ['MY', 'MYS', 'Malaysia', 'Malaisie'],
                ['MV', 'MDV', 'Maldives', 'Maldives'],
                ['ML', 'MLI', 'Mali', 'Mali'],
                ['MT', 'MLT', 'Malta', 'Malte'],
                ['MQ', 'MTQ', 'Martinique', 'Martinique'],
                ['MR', 'MRT', 'Mauritania', 'Mauritanie'],
                ['MU', 'MUS', 'Mauritius', 'Maurice'],
                ['MX', 'MEX', 'Mexico', 'Mexique'],
                ['MC', 'MCO', 'Monaco', 'Monaco'],
                ['MN', 'MNG', 'Mongolia', 'Mongolie'],
                ['MD', 'MDA', 'Republic of Moldova', 'République de Moldova'],
                ['MS', 'MSR', 'Montserrat', 'Montserrat'],
                ['MA', 'MAR', 'Morocco', 'Maroc'],
                ['MZ', 'MOZ', 'Mozambique', 'Mozambique'],
                ['OM', 'OMN', 'Oman', 'Oman'],
                ['NA', 'NAM', 'Namibia', 'Namibie'],
                ['NR', 'NRU', 'Nauru', 'Nauru'],
                ['NP', 'NPL', 'Nepal', 'Népal'],
                ['NL', 'NLD', 'Netherlands', 'Pays-Bas'],
                ['AN', 'ANT', 'Netherlands Antilles', 'Antilles Néerlandaises'],
                ['AW', 'ABW', 'Aruba', 'Aruba'],
                ['NC', 'NCL', 'New Caledonia', 'Nouvelle-Calédonie'],
                ['VU', 'VUT', 'Vanuatu', 'Vanuatu'],
                ['NZ', 'NZL', 'New Zealand', 'Nouvelle-Zélande'],
                ['NI', 'NIC', 'Nicaragua', 'Nicaragua'],
                ['NE', 'NER', 'Niger', 'Niger'],
                ['NG', 'NGA', 'Nigeria', 'Nigéria'],
                ['NU', 'NIU', 'Niue', 'Niué'],
                ['NF', 'NFK', 'Norfolk Island', 'Île Norfolk'],
                ['NO', 'NOR', 'Norway', 'Norvège'],
                ['MP', 'MNP', 'Northern Mariana Islands', 'Îles Mariannes du Nord'],
                ['UM', 'UMI', 'United States Minor Outlying Islands', 'Îles Mineures Éloignées des États-Unis'],
                ['FM', 'FSM', 'Federated States of Micronesia', 'États Fédérés de Micronésie'],
                ['MH', 'MHL', 'Marshall Islands', 'Îles Marshall'],
                ['PW', 'PLW', 'Palau', 'Palaos'],
                ['PK', 'PAK', 'Pakistan', 'Pakistan'],
                ['PA', 'PAN', 'Panama', 'Panama'],
                ['PG', 'PNG', 'Papua New Guinea', 'Papouasie-Nouvelle-Guinée'],
                ['PY', 'PRY', 'Paraguay', 'Paraguay'],
                ['PE', 'PER', 'Peru', 'Pérou'],
                ['PH', 'PHL', 'Philippines', 'Philippines'],
                ['PN', 'PCN', 'Pitcairn', 'Pitcairn'],
                ['PL', 'POL', 'Poland', 'Pologne'],
                ['PT', 'PRT', 'Portugal', 'Portugal'],
                ['GW', 'GNB', 'Guinea-Bissau', 'Guinée-Bissau'],
                ['TL', 'TLS', 'Timor-Leste', 'Timor-Leste'],
                ['PR', 'PRI', 'Puerto Rico', 'Porto Rico'],
                ['QA', 'QAT', 'Qatar', 'Qatar'],
                ['RE', 'REU', 'Réunion', 'Réunion'],
                ['RO', 'ROU', 'Romania', 'Roumanie'],
                ['RU', 'RUS', 'Russian Federation', 'Fédération de Russie'],
                ['RW', 'RWA', 'Rwanda', 'Rwanda'],
                ['SH', 'SHN', 'Saint Helena', 'Sainte-Hélène'],
                ['KN', 'KNA', 'Saint Kitts and Nevis', 'Saint-Kitts-et-Nevis'],
                ['AI', 'AIA', 'Anguilla', 'Anguilla'],
                ['LC', 'LCA', 'Saint Lucia', 'Sainte-Lucie'],
                ['PM', 'SPM', 'Saint-Pierre and Miquelon', 'Saint-Pierre-et-Miquelon'],
                ['VC', 'VCT', 'Saint Vincent and the Grenadines', 'Saint-Vincent-et-les Grenadines'],
                ['SM', 'SMR', 'San Marino', 'Saint-Marin'],
                ['ST', 'STP', 'Sao Tome and Principe', 'Sao Tomé-et-Principe'],
                ['SA', 'SAU', 'Saudi Arabia', 'Arabie Saoudite'],
                ['SN', 'SEN', 'Senegal', 'Sénégal'],
                ['SC', 'SYC', 'Seychelles', 'Seychelles'],
                ['SL', 'SLE', 'Sierra Leone', 'Sierra Leone'],
                ['SG', 'SGP', 'Singapore', 'Singapour'],
                ['SK', 'SVK', 'Slovakia', 'Slovaquie'],
                ['VN', 'VNM', 'Vietnam', 'Viet Nam'],
                ['SI', 'SVN', 'Slovenia', 'Slovénie'],
                ['SO', 'SOM', 'Somalia', 'Somalie'],
                ['ZA', 'ZAF', 'South Africa', 'Afrique du Sud'],
                ['ZW', 'ZWE', 'Zimbabwe', 'Zimbabwe'],
                ['ES', 'ESP', 'Spain', 'Espagne'],
                ['EH', 'ESH', 'Western Sahara', 'Sahara Occidental'],
                ['SD', 'SDN', 'Sudan', 'Soudan'],
                ['SR', 'SUR', 'Suriname', 'Suriname'],
                ['SJ', 'SJM', 'Svalbard and Jan Mayen', 'Svalbard etÎle Jan Mayen'],
                ['SZ', 'SWZ', 'Swaziland', 'Swaziland'],
                ['SE', 'SWE', 'Sweden', 'Suède'],
                ['CH', 'CHE', 'Switzerland', 'Suisse'],
                ['SY', 'SYR', 'Syrian Arab Republic', 'République Arabe Syrienne'],
                ['TJ', 'TJK', 'Tajikistan', 'Tadjikistan'],
                ['TH', 'THA', 'Thailand', 'Thaïlande'],
                ['TG', 'TGO', 'Togo', 'Togo'],
                ['TK', 'TKL', 'Tokelau', 'Tokelau'],
                ['TO', 'TON', 'Tonga', 'Tonga'],
                ['TT', 'TTO', 'Trinidad and Tobago', 'Trinité-et-Tobago'],
                ['AE', 'ARE', 'United Arab Emirates', 'Émirats Arabes Unis'],
                ['TN', 'TUN', 'Tunisia', 'Tunisie'],
                ['TR', 'TUR', 'Turkey', 'Turquie'],
                ['TM', 'TKM', 'Turkmenistan', 'Turkménistan'],
                ['TC', 'TCA', 'Turks and Caicos Islands', 'Îles Turks et Caïques'],
                ['TV', 'TUV', 'Tuvalu', 'Tuvalu'],
                ['UG', 'UGA', 'Uganda', 'Ouganda'],
                ['UA', 'UKR', 'Ukraine', 'Ukraine'],
                ['MK', 'MKD', 'The Former Yugoslav Republic of Macedonia', 'L\'ex-République Yougoslave de Macédoine'],
                ['EG', 'EGY', 'Egypt', 'Égypte'],
                ['GB', 'GBR', 'United Kingdom', 'Royaume-Uni'],
                ['IM', 'IMN', 'Isle of Man', 'Île de Man'],
                ['TZ', 'TZA', 'United Republic Of Tanzania', 'République-Unie de Tanzanie'],
                ['US', 'USA', 'United States', 'États-Unis'],
                ['VI', 'VIR', 'U.S. Virgin Islands', 'Îles Vierges des États-Unis'],
                ['BF', 'BFA', 'Burkina Faso', 'Burkina Faso'],
                ['UY', 'URY', 'Uruguay', 'Uruguay'],
                ['UZ', 'UZB', 'Uzbekistan', 'Ouzbékistan'],
                ['VE', 'VEN', 'Venezuela', 'Venezuela'],
                ['WF', 'WLF', 'Wallis and Futuna', 'Wallis et Futuna'],
                ['WS', 'WSM', 'Samoa', 'Samoa'],
                ['YE', 'YEM', 'Yemen', 'Yémen'],
                ['CS', 'SCG', 'Serbia and Montenegro', 'Serbie-et-Monténégro'],
                ['ZM', 'ZMB', 'Zambia', 'Zambie']
            ];

            foreach ($pays as $ps) {
                Country::create([
                        'alpha2' => $ps[0],
                        'alpha3' => $ps[1],
                        'nom_en' => $ps[2],
                        'nom_fr' => $ps[3],
                    ]);
            }
        }
    }
}
