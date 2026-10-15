package coba;
public static void main(String[]args){}
class Kue{
    String rasa;
    int harga;

    Kue(String rasaKue, int hargaKue){
        rasa = rasaKue;
        harga = hargaKue;
    }
}
public class Clobcon {
    public static void main(String[] args) {
        
        Kue kueSaya = new Kue("coklat",100000);
        System.out.println(kueSaya.rasa);
        System.out.println(kueSaya.harga);
    }
}
