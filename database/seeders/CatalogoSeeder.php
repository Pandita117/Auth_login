<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Catálogo fijo de 3 categorías
        $electronica = Categoria::firstOrCreate(['nombre' => 'Electrónica']);
        $papeleria   = Categoria::firstOrCreate(['nombre' => 'Papelería']);
        $accesorios  = Categoria::firstOrCreate(['nombre' => 'Accesorios']);

        // 2. Productos reales para probar búsqueda, filtros y paginación (10, 15, 25, 50)
        $productos = [
            // Electrónica
            ['producto' => 'Laptop Lenovo IdeaPad 15"', 'descripcion' => 'Procesador Ryzen 5, 16GB RAM, SSD 512GB.', 'categoria_id' => $electronica->id, 'precio' => 12499.00, 'cantidad' => 8, 'estado' => 'Disponible'],
            ['producto' => 'Monitor Samsung 24" Full HD', 'descripcion' => 'Panel IPS 75Hz con entrada HDMI y VGA.', 'categoria_id' => $electronica->id, 'precio' => 2890.50, 'cantidad' => 14, 'estado' => 'Disponible'],
            ['producto' => 'Teclado Mecánico Redragon', 'descripcion' => 'Switches Blue retroiluminado RGB en español.', 'categoria_id' => $electronica->id, 'precio' => 850.00, 'cantidad' => 0, 'estado' => 'Agotado'],
            ['producto' => 'Mouse Inalámbrico Logitech M185', 'descripcion' => 'Receptor USB nano, batería de hasta 12 meses.', 'categoria_id' => $electronica->id, 'precio' => 299.00, 'cantidad' => 35, 'estado' => 'Disponible'],
            ['producto' => 'Disco Duro Externo 1TB USB 3.0', 'descripcion' => 'Almacenamiento portátil compatible con Windows y macOS.', 'categoria_id' => $electronica->id, 'precio' => 1150.00, 'cantidad' => 12, 'estado' => 'Disponible'],
            ['producto' => 'Audífonos Diadema Sony WH-CH520', 'descripcion' => 'Bluetooth 5.2, batería de hasta 50 horas.', 'categoria_id' => $electronica->id, 'precio' => 999.00, 'cantidad' => 6, 'estado' => 'Disponible'],
            ['producto' => 'Memoria USB Kingston 64GB', 'descripcion' => 'USB 3.2 Gen 1 de alta velocidad.', 'categoria_id' => $electronica->id, 'precio' => 185.00, 'cantidad' => 42, 'estado' => 'Disponible'],
            ['producto' => 'Webcam Full HD 1080p con Micrófono', 'descripcion' => 'Cámara web con clip universal para videollamadas.', 'categoria_id' => $electronica->id, 'precio' => 540.00, 'cantidad' => 0, 'estado' => 'Agotado'],
            ['producto' => 'Impresora Multifuncional Epson EcoTank', 'descripcion' => 'Sistema de tanque de tinta continua Wi-Fi.', 'categoria_id' => $electronica->id, 'precio' => 4399.00, 'cantidad' => 4, 'estado' => 'Disponible'],
            ['producto' => 'Tablet Samsung Galaxy Tab A9', 'descripcion' => 'Pantalla de 8.7 pulgadas, 64GB almacenamiento.', 'categoria_id' => $electronica->id, 'precio' => 2650.00, 'cantidad' => 9, 'estado' => 'Disponible'],

            // Papelería
            ['producto' => 'Cuaderno Profesional Raya 100 Hojas', 'descripcion' => 'Pasta dura resistente con espiral doble.', 'categoria_id' => $papeleria->id, 'precio' => 48.50, 'cantidad' => 80, 'estado' => 'Disponible'],
            ['producto' => 'Paquete de 500 Hojas Blancas Carta', 'descripcion' => 'Papel bond de 75g alta blancura.', 'categoria_id' => $papeleria->id, 'precio' => 135.00, 'cantidad' => 25, 'estado' => 'Disponible'],
            ['producto' => 'Caja de Bolígrafos Punto Medio Azul (12 pzas)', 'descripcion' => 'Tinta de secado rápido y trazo suave.', 'categoria_id' => $papeleria->id, 'precio' => 65.00, 'cantidad' => 40, 'estado' => 'Disponible'],
            ['producto' => 'Marcadores Permanentes Negros (4 pzas)', 'descripcion' => 'Punta cincel resistente al agua.', 'categoria_id' => $papeleria->id, 'precio' => 78.00, 'cantidad' => 0, 'estado' => 'Agotado'],
            ['producto' => 'Carpeta Argollas 2 Pulgadas Blanca', 'descripcion' => 'Arillo redondo metálico con bolsa interior.', 'categoria_id' => $papeleria->id, 'precio' => 89.00, 'cantidad' => 19, 'estado' => 'Disponible'],
            ['producto' => 'Notas Adhesivas Colores Pastel (400 hojas)', 'descripcion' => 'Cubo de notas 3x3 pulgadas.', 'categoria_id' => $papeleria->id, 'precio' => 54.00, 'cantidad' => 50, 'estado' => 'Disponible'],
            ['producto' => 'Calculadora Científica Casio fx-82MS', 'descripcion' => '240 funciones, pantalla de 2 líneas.', 'categoria_id' => $papeleria->id, 'precio' => 340.00, 'cantidad' => 11, 'estado' => 'Disponible'],
            ['producto' => 'Engrapadora Metálica Estándar', 'descripcion' => 'Capacidad para engrapar hasta 25 hojas.', 'categoria_id' => $papeleria->id, 'precio' => 145.00, 'cantidad' => 15, 'estado' => 'Disponible'],
            ['producto' => 'Juego de Geometría Flexible 4 Piezas', 'descripcion' => 'Regla 30cm, escuadras y transportador irrompibles.', 'categoria_id' => $papeleria->id, 'precio' => 62.00, 'cantidad' => 0, 'estado' => 'Agotado'],
            ['producto' => 'Tijeras de Oficina Acero Inoxidable 8"', 'descripcion' => 'Mango ergonómico de goma suave.', 'categoria_id' => $papeleria->id, 'precio' => 59.00, 'cantidad' => 28, 'estado' => 'Disponible'],

            // Accesorios
            ['producto' => 'Mochila Ejecutiva Antirrobo 15.6"', 'descripcion' => 'Puerto de carga USB integrado y tela impermeable.', 'categoria_id' => $accesorios->id, 'precio' => 690.00, 'cantidad' => 13, 'estado' => 'Disponible'],
            ['producto' => 'Soporte Plegable de Aluminio para Laptop', 'descripcion' => 'Ajustable a 6 niveles de altura, ventilado.', 'categoria_id' => $accesorios->id, 'precio' => 320.00, 'cantidad' => 22, 'estado' => 'Disponible'],
            ['producto' => 'Hub USB-C 7 en 1 con HDMI 4K', 'descripcion' => 'Incluye 3 puertos USB 3.0, lector SD y carga PD.', 'categoria_id' => $accesorios->id, 'precio' => 780.00, 'cantidad' => 7, 'estado' => 'Disponible'],
            ['producto' => 'Alfombrilla Mousepad XL Antideslizante', 'descripcion' => 'Medidas 80x30cm con bordes cosidos.', 'categoria_id' => $accesorios->id, 'precio' => 240.00, 'cantidad' => 18, 'estado' => 'Disponible'],
            ['producto' => 'Cable HDMI 2.1 Trenzado 2 Metros', 'descripcion' => 'Soporta resolución 4K a 120Hz y 8K a 60Hz.', 'categoria_id' => $accesorios->id, 'precio' => 195.00, 'cantidad' => 30, 'estado' => 'Disponible'],
            ['producto' => 'Funda Acolchada para Laptop 14"', 'descripcion' => 'Interior de felpa suave y bolsillo frontal.', 'categoria_id' => $accesorios->id, 'precio' => 275.00, 'cantidad' => 0, 'estado' => 'Agotado'],
            ['producto' => 'Cargador Rápido USB-C 65W GaN', 'descripcion' => 'Compatible con laptops, tablets y smartphones.', 'categoria_id' => $accesorios->id, 'precio' => 650.00, 'cantidad' => 10, 'estado' => 'Disponible'],
            ['producto' => 'Kit de Limpieza para Pantallas y Teclados', 'descripcion' => 'Spray antiestático y paño de microfibra.', 'categoria_id' => $accesorios->id, 'precio' => 120.00, 'cantidad' => 33, 'estado' => 'Disponible'],
            ['producto' => 'Regulador de Voltaje 8 Contactos', 'descripcion' => 'Protección contra sobrecargas y picos de corriente.', 'categoria_id' => $accesorios->id, 'precio' => 580.00, 'cantidad' => 5, 'estado' => 'Disponible'],
            ['producto' => 'Base Enfriadora con 2 Ventiladores LED', 'descripcion' => 'Silenciosa, compatible con equipos de hasta 17".', 'categoria_id' => $accesorios->id, 'precio' => 410.00, 'cantidad' => 16, 'estado' => 'Disponible'],
        ];

        foreach ($productos as $item) {
            Producto::firstOrCreate(
                ['producto' => $item['producto']],
                $item
            );
        }
    }
}