<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ProdukController extends BaseController
{
    protected $productModel;

    function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        return view('Produk/index', [
            'products' => $this->productModel->findAll()
        ]);
    }

    public function create()
    {
        $foto = $this->request->getFile('foto');
        $namaFoto = '';

        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(FCPATH . 'img', $namaFoto);
        }

        $this->productModel->insert([
            'nama'   => $this->request->getPost('nama'),
            'harga'  => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah'),
            'foto'   => $namaFoto,
        ]);
        
        return redirect()->to(base_url('produk'))->with('success', 'Data berhasil ditambahkan');
    }
    public function edit($id)
{
    $dataProduk = $this->productModel->find($id);

    $dataForm = [
        'nama' => $this->request->getPost('nama'),
        'harga' => $this->request->getPost('harga'),
        'jumlah' => $this->request->getPost('jumlah') 
    ];

    if ($this->request->getPost('check') == 1) {
        if ($dataProduk['foto'] != '' and file_exists("img/" . $dataProduk['foto'] . "")) {
            unlink("img/" . $dataProduk['foto']);
        }

        $dataFoto = $this->request->getFile('foto');

        if ($dataFoto->isValid()) {
            $fileName = $dataFoto->getRandomName();
            $dataFoto->move('img/', $fileName);
            
            $dataForm['foto'] = $fileName;
        }
    }

    $this->productModel->update($id, $dataForm);

    return redirect('produk')->with('success', 'Data Berhasil Diubah');
}

public function delete($id)
{
    $dataProduk = $this->productModel->find($id);
    $this->productModel->delete($id);

    return redirect('produk')->with('success', 'Data Berhasil Dihapus');
}
}
