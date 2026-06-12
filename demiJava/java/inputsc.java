
import java.util.Scanner;

public class inputsc {
    public static void main(String[] args) {
        Scanner a = new Scanner(System.in);
        System.out.print("masukan nama anda :");
        String nama =a.nextLine();
        System.out.println("nama anda adalah "+nama);

       
        System.out.print("masukan nilai a :");
        int nilai1 = a.nextInt();
        System.out.print("masukan nilai b :");
        int nilai2 = a.nextInt();
        int hasil = nilai1 + nilai2;
        System.out.println("hasil dari "+nilai1+" + "+nilai2+" = "+hasil);   
        System.out.printf("hasil dari %d + %d = %d",nilai1,nilai2,hasil);   

        
    }   
}
