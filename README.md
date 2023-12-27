# XmlProcessor Projesi

Bu proje, XML verilerini işleyen ve veritabanına kaydeden bir Laravel uygulamasını içerir.

## Kurulum

1. Proje dosyalarını indirin veya klonlayın:

```
git clone <repo_url>
```

2. Composer ile bağımlılıkları yükleyin:

```
composer install
```

3. `.env` dosyasını oluşturun ve veritabanı bağlantı ayarlarınızı belirtin:

```
cp .env.example .env
```

4. Uygulama anahtarını oluşturun:

```
php artisan key:generate
```

5. Veritabanını oluşturmak için migrasyonları çalıştırın:

```
php artisan migrate
```

## Kullanım

Komut dosyasını kullanarak XML verilerini işleyebilirsiniz:

```
php artisan process:xml
```

Bu komut, XML verilerini indirecek, işleyecek ve veritabanına kaydedecektir.

## Testler

Testleri çalıştırmak için:

```
php artisan test
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
