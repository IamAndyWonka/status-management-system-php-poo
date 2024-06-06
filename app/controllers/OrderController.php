<?php
// App/Controllers/OrderController.php
namespace App\Controllers;

use App\Models\Order;
use PDO;
use FPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function getAllOrders() {
        $order = new Order();
        return $order->getAllOrders();
    }

    public function getOrderById($id) {
        $order = new Order();
        return $order->getOrderById($id);
    }

    public function updateOrder($id, $order_number, $client_id, $status) {
        $order = new Order();
        return $order->updateOrder($id, $order_number, $client_id, $status);
    }

    public function deleteOrder($id) {
        $order = new Order();
        return $order->deleteOrder($id);
    }

    public function createOrder($client_id, $status) {
        try {
            $order_number = $this->generateUniqueOrderNumber();
            return $this->orderModel->createOrder($order_number, $client_id, $status);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getOrderCount() {
        return $this->orderModel->countOrders();
    }

    public function searchOrder($order_number) {
        try {
            return $this->orderModel->getOrderByNumber($order_number);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    private function generateUniqueOrderNumber() {
        do {
            $order_number = date('Ymd') . rand(100000, 999999);
        } while ($this->orderModel->orderNumberExists($order_number));
        return $order_number;
    }

    public function getOrdersByDay() {
        return $this->orderModel->getOrdersByDay();
    }

    public function exportOrdersToPDF() {
        $orders = $this->getAllOrders();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Numero de Orden');
        $pdf->Cell(40, 10, 'Cliente');
        $pdf->Cell(40, 10, 'Status');
        $pdf->Cell(40, 10, 'Fecha de Creacion');
        $pdf->Ln();

        foreach ($orders as $order) {
            $pdf->Cell(40, 10, $order['order_number']);
            $pdf->Cell(40, 10, $order['client_name']);
            $pdf->Cell(40, 10, $order['status_tracking']);
            $pdf->Cell(40, 10, $order['created_at']);
            $pdf->Ln();
        }

        $pdf->Output('D', 'orders.pdf');
    }

    public function exportOrdersToExcel() {
        $orders = $this->getAllOrders();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Numero de Orden');
        $sheet->setCellValue('B1', 'Cliente');
        $sheet->setCellValue('C1', 'Status');
        $sheet->setCellValue('D1', 'Fecha de Creacion');

        $row = 2;
        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $row, $order['order_number']);
            $sheet->setCellValue('B' . $row, $order['client_name']);
            $sheet->setCellValue('C' . $row, $order['status_tracking']);
            $sheet->setCellValue('D' . $row, $order['created_at']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        
        // Enviar el archivo al navegador en lugar de guardarlo en el servidor
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="orders.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
}
?>
