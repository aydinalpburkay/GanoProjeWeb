<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ortalama Hesaplama</title>
</head>
<body>
    <h2>Ders ve Not Bilgilerini Girin</h2>
    <form method="post" action="">
        <div id="dersler">
            <div class="ders">
                <label for="isim[]">Ders Adı:</label>
                <input type="text" name="isim[]" required>
                <label for="vize[]">Vize Notu:</label>
                <input type="number" name="vize[]" min="0" max="100" required>
                <label for="final[]">Final Notu:</label>
                <input type="number" name="final[]" min="0" max="100" required>
                <label for="kredi[]">Kredi:</label>
                <select name="kredi[]" required>
                    <?php for ($i = 0.5; $i <= 30; $i += 0.5) {
                        echo "<option value=\"$i\">$i</option>";
                    } ?>
                </select>
            </div>
        </div>
        <button type="button" onclick="addDers()">Ders Ekle</button>
        <br><br>
        <input type="submit" name="submit" value="Hesapla">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        class Ders {
            public $isim;
            public $vize;
            public $final;
            public $kredi;
            
            public function __construct($isim, $vize, $final, $kredi) {
                $this->isim = $isim;
                $this->vize = $vize;
                $this->final = $final;
                $this->kredi = $kredi;
            }
            
            public function ortalama() {
                return number_format(($this->vize * 0.4) + ($this->final * 0.6), 2);
            }

            public function harfNotu() {
                $ortalama = $this->ortalama();
                if ($this->final < 45) {
                    return 'FD';
                } elseif ($ortalama < 30) {
                    return 'FF';
                } elseif ($ortalama < 40) {
                    return 'FD';
                } elseif ($ortalama < 45) {
                    return 'DD';
                } elseif ($ortalama < 50) {
                    return 'DC';
                } elseif ($ortalama < 57) {
                    return 'CC';
                } elseif ($ortalama < 65) {
                    return 'CB';
                } elseif ($ortalama < 75) {
                    return 'BB';
                } elseif ($ortalama < 85) {
                    return 'BA';
                } else {
                    return 'AA';
                }
            }

            public function harfNotuDegeri() {
                $harfNotu = $this->harfNotu();
                switch ($harfNotu) {
                    case 'FF':
                        return 0;
                    case 'FD':
                        return 0.5;
                    case 'DD':
                        return 1;
                    case 'DC':
                        return 1.5;
                    case 'CC':
                        return 2;
                    case 'CB':
                        return 2.5;
                    case 'BB':
                        return 3;
                    case 'BA':
                        return 3.5;
                    case 'AA':
                        return 4;
                    default:
                        return 0; // Varsayılan olarak FF'ye dönüştür
                }
            }
        }

        $isimler = $_POST['isim'];
        $vizeler = $_POST['vize'];
        $finaller = $_POST['final'];
        $krediler = $_POST['kredi'];

        $dersler = [];
        for ($i = 0; $i < count($isimler); $i++) {
            $dersler[] = new Ders($isimler[$i], $vizeler[$i], $finaller[$i], $krediler[$i]);
        }

        $toplamKredi = 0;
        $toplamHarfNotuDegeri = 0;

        echo "<h3>Ders Notları ve Ortalamaları</h3>";
        foreach ($dersler as $ders) {
            $ortalama = $ders->ortalama();
            echo "Ders: {$ders->isim}, Vize: {$ders->vize}, Final: {$ders->final}, Ortalama: {$ortalama}, Kredi: {$ders->kredi}, Harf Notu: {$ders->harfNotu()}<br>";
            $toplamKredi += $ders->kredi;
            $toplamHarfNotuDegeri += $ders->harfNotuDegeri() * $ders->kredi;
        }

        $genelHarfNotu = number_format($toplamHarfNotuDegeri / $toplamKredi, 2);
        echo "<h3>Genel Harf Notu: " . $genelHarfNotu . "</h3>";
    }
    ?>

    <script>
        function addDers() {
            var derslerDiv = document.getElementById('dersler');
            var yeniDersDiv = document.createElement('div');
            yeniDersDiv.classList.add('ders');

            var dersHtml = `
                <br><label for="isim[]">Ders Adı:</label>
                <input type="text" name="isim[]" required>
                <label for="vize[]">Vize Notu:</label>
                <input type="number" name="vize[]" min="0" max="100" required>
                <label for="final[]">Final Notu:</label>
                <input type="number" name="final[]" min="0" max="100" required>
                <label for="kredi[]">Kredi:</label>
                <select name="kredi[]" required>
                    <?php for ($i = 0.5; $i <= 30; $i += 0.5) {
                        echo "<option value=\"$i\">$i</option>";
                    } ?>
                </select>
            `;
            yeniDersDiv.innerHTML = dersHtml;
            derslerDiv.appendChild(yeniDersDiv);
        }
    </script>
</body>
</html>
