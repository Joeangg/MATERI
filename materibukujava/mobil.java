public class mobil {
    String warna;
    int tahun;
    public void isidata(String warnamobil, int tahunproduksi){
        warna  = warnamobil;
        tahun = tahunproduksi;
    }
    String ambilwarna(){
        return warna;
    }
    int ambiltahun(){
        return tahun; 
    }
    public class mobil2{
        public static void main(String[] args) {
            mobil mobilbaru = new mobil();
            mobilbaru.isidata("merah",2020);
            
        }
    } 
}
