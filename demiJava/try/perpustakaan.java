class perpustakaan{
        public static void main(String[]ARGS)throws Exception{
     class Buku{
        int KodeBuku;
        String JudulBuku;
        String Penerbit;
        String NamaPenulis;
       protected int JumlahBuku;
       private int NoRak;
     public void list(){
    System.out.println("kode buku :"+ KodeBuku);
    System.out.println("Judul Buku :" + JudulBuku);
    System.out.println("Penerbit :" + Penerbit);
    System.out.println("Nama Penulis :" +NamaPenulis);
    System.out.println("Jumlah Buku :" +JumlahBuku);
    System.out.println("Nomor Rak :" + NoRak);    
}     
    }
   Buku bukuperpus = new Buku();
    bukuperpus.KodeBuku = 001;
    bukuperpus.JudulBuku = "Bumi manusia";
    bukuperpus.Penerbit = "nusantara";    
    bukuperpus.NamaPenulis = "Pramodea Ananda Toer";
    bukuperpus.JumlahBuku = 12;
    bukuperpus.NoRak = 11;
    bukuperpus.list();

     Buku bukuperpus2 = new Buku();
    bukuperpus2.KodeBuku = 002;
    bukuperpus2.JudulBuku = "samudra";
    bukuperpus2.Penerbit = "gramedia";    
    bukuperpus2.NamaPenulis = "aji darma putra";
    bukuperpus2.JumlahBuku = 20;
    bukuperpus2.NoRak = 12;
    bukuperpus2.list();
 }
}