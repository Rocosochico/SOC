<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soc extends CI_Controller {

		public function index()
	{
		$this->load->helper('directory');
        $this->load->helper('file');
        $this->load->helper('text');
        $map = directory_map('public/ordenes');
        $i = 0;
        foreach ($map as $maps){
            echo "<b>Archivo: </b>" . $maps . "<br>";
            $string = read_file('public/ordenes/' . $maps);
            //echo $string . "<br>";
            //CADENA
           $cadena = substr($string, 0, 29);
            echo $cadena . "<br>";
            //ORDEN DE COMPRA
            $oc = substr($string, 49, 30);
            echo $oc . "<br>";
            //FECHA DE ENVIO
            $env = strpos($string, "DTM");
            $fenvio = substr($string, $env, 21);
            echo $fenvio . "<br>";
            //FECHA DE ENTREGA
            $ent = strpos($string, "DTM ");
            $fentrega = substr($string, $ent, 21);
            echo $fentrega . "<br>";
            //CODIGO LOCAL
            $loc = strpos($string, "LOC");
            $codLoc = substr($string, $loc, 21);
            echo $codLoc . "<br>";
            
            
            //LINEAS CON PRODUCTOS
            $y = substr_count($string, 'LIN');
            $lin = 0;  
            $prod = 0;
            $cant = 0;
            $pn = 0;
            $pu = 0;
            $dc = 0;
            $mt = 0;
            for ($x = 1; $x <= $y; $x++) {
                $lin = strpos($string, "LIN", $lin+8);
                $linProd = substr($string, $lin, 25);
                echo "Línea: " . $linProd . "<br>";
                //PRODUCTO
                $prod = strpos($string, "IMDF", $prod+8);
                $producto = substr($string, $prod, 45);
                echo "Producto: " . $producto . "<br>";
                //CANTIDAD
                $cant = strpos($string, "QTY ", $cant+8);
                $cantidad = substr($string, $cant, 24);
                echo "Cantidad: " . $cantidad . "<br>";
                //PRECIO NETO
                $pn = strpos($string, "MOA", $pn+8);
                $precioNeto = substr($string, $pn, 24);
                echo "Precio Neto: " . $precioNeto . "<br>";
                //PRECIO UNITARIO
                $pu = strpos($string, "PRIA", $pu+8);
                $precioUnitario = substr($string, $pu, 29);
                echo "Precio Unitario: " . $precioUnitario . "<br>";
                //DESCUENTOS Y CARGOS
                $dc = strpos($string, "ALCC", $dc+8);
                $decCar = substr($string, $dc, 9);
                echo "Descuento y/o Cargo: " . $decCar . "<br>";
                //MONTO
                $mt = strpos($string, "MOA ", $mt+8);
                $monto = substr($string, $mt, 24);
                echo "Monto: " . $monto . "<br>";
                
                //INSERTAR DATOS EN TABLA LINEA_OC               
                $datosLOC=array("Linea_OC"=>$linProd,"Desc_Producto"=>$producto,"Cant_Producto"=>$cantidad,"Precio_Unitario_Producto"=>$precioUnitario,"Descto"=>$decCar,"Precio_Total_Producto"=>$monto,"id_OC"=>$oc);
                $insert=$this->LineaOC->insert($datosLOC); 
                echo "Datos ingresados en LINEAOC " . $linProd . "<br><br>";
            }
            //Insertar datos en Tabla Orden_Compra
            
            $datosOC=array("id_OC"=>$oc,"F_Envio"=>$fenvio,"F_Entrega"=>$fentrega,"id_Local"=>$codLoc,"id_Cadena"=>$cadena);
            $insert=$this->Ordenes->insert($datosOC); 
            echo "<br>Orden de Compra Ingresada<br>";
            echo "<b>Fin del Archivo.</b><br><br>";
        }
          
        $this->layout->setLayout('carga');
        $this->layout->setTitle("Portal SOC");
        $this->layout->setKeywords("Portal SOC");
        $this->layout->setDescripcion("Carga Ordenes de Compra");
        //$this->layout->css(array(base_url()."public/css/"));
        //$this->layout->js(array(base_url()."public/js/"));
        $this->layout->view('principal'); 
	}
}
