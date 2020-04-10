<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class DesalaporController extends Controller
{

    public function actionIndex()
    {
        $this->stdout("\n");
        $this->stdout("DesaLaporCovid",Console::BOLD, Console::FG_YELLOW );
        $this->stdout("Menu tersedia:\n");
        $this->stdout("- `php yii desalapor/install` Untuk install struktur data ");
        $this->stdout("\n\n\n\n\n\n");
    }


    public function actionInstall()
    {
        $this->stdout("DesaLaporCovid install\n",Console::BOLD, Console::FG_YELLOW );
        $this->stdout("PERINGATAN: Proses ini akan menghapus dan membuat ulang tabel basis data.\n");

        if (Console::confirm("Anda yakin?") !== true) {
            $this->stdout("Aksi dibatalkan.\n");
            return ExitCode::OK;
        }
        $this->refreshDbStructures();
        $this->insertInitialData();

        $username = 'superadmin';
        $password = $this->randomPassword(8);
        $this->stdout("\n\n\n");
        $this->stdout("Membuat user baru dengan username: ");
        $this->stdout("superadmin\n", Console::BOLD);
        $this->stdout("Password: ");
        $this->stdout($password, Console::BOLD);
        $this->stdout("\n\n\n");
        $this->actionAddsuperadmin($username, $password);
        $this->stdout("Proses selesai\n", Console::BOLD);
        return ExitCode::OK;
    }


    public function actionAddsuperadmin($username = 'superadmin', $password = '')
    {
        $user = new \app\models\User;
        $user->username = $username;
        $user->password = md5($password);
        $user->userType = \app\models\User::LEVEL_ADMIN;
        $user->status = \app\models\User::STATUS_ACTIVE;
        return $user->save();
    }


    protected function randomPassword( $length ) {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),0,$length);
    }

    protected function refreshDbStructures()
    {
        $migration = new \yii\console\controllers\MigrateController('migrate', \Yii::$app);
        $migration->runAction('fresh', ['interactive' => false ]);

    }

    protected function insertInitialData()
    {
        //insert initial data 
        $this->stdout("Memasukkan data awal\n", Console::BOLD);

        //jenis_laporan 
        $this->stdout("1. Jenis laporan\n");
        $this->importFromCSV(\app\models\table\JenisLaporan::tableName(), 'jenis_laporan.csv');

        //negara
        $this->stdout("2. Negara\n");
        $this->importFromCsv(\app\models\table\Negara::tableName(), 'negara.csv');

        //provinsi
        $this->stdout("3. Provinsi\n");
        $this->importFromCsv(\app\models\table\Provinsi::tableName(), 'provinsi.csv');

        //kabupaten
        $this->stdout("4. Kabupaten\n");
        $this->importFromCsv(\app\models\table\Kabupaten::tableName(), 'kabupaten.csv');
        
        //kecamatan
        $this->stdout("5. Kecamatan\n");
        $this->importFromCsv(\app\models\table\Kecamatan::tableName(), 'kecamatan.csv');

        //kelurahan 
        $this->stdout("6. Kelurahan\n");
        $this->importFromCsv(\app\models\table\Kelurahan::tableName(), 'kelurahan.csv');
    }

    protected function importFromCsv($tableName, $csvFilename)
    {
        $basePath = \Yii::getAlias('@app');
        $filename = realpath(join('/',[$basePath, 'data/csv', $csvFilename]));
        $data = array_map('str_getcsv', file($filename));
        $columns = array_shift($data);
        return \Yii::$app->db->createCommand()->batchInsert($tableName, $columns, $data);
    }

}
