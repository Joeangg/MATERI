import java.util.Scanner;

public class sw {
    public static void main(String[] args) {
        Scanner a = new Scanner(System.in);
      String masukan;
     
        System.out.print("nama anda adalah ");
         masukan=a.next();
 try{
        switch (masukan){
        case "ruri":
            System.out.println("warga RT01");
            System.out.println("melawai no 11");
            break;
            case"safi":
            System.out.println("warga RT01");
            System.out.println("melawai no 24");
            break;
            case "daniel":
            System.out.println("warga RT01");
            System.out.println("melawai no 04");
            default:
            System.out.println("nama tidak ditemukan");
            break;
        }} catch (Exception e){
         }
         System.out.println("terimakasih");
    }
}
