import java.util.Scanner;
public class test1 {
    public static void main(String[] args) {
      Scanner a = new Scanner(System.in);
        System.out.print("Masukkan nama: ");
        String nama = a.nextLine();
        System.out.print("Masukkan nilai: ");
        int nilai= a.nextInt();
        System.out.print("kehadiran: ");
        int kehadiran = a.nextInt();
        System.out.print("tugas:");
        boolean tugas = a.nextBoolean();
        if(nilai>=75){
            System.out.println("lulus");
            
        } if(kehadiran>=80){
            System.out.println("lulus");
        } if(tugas==true){
            System.out.println("lulus");
        } else {
            System.out.println("tidak lulus");
        }
    }
}
