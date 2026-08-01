import java.util.Scanner;
class nif {
    public static void main(String[] args) {
        Scanner baru = new Scanner(System.in);

        System.out.print("Masukkan nilai: ");
       int nilai = baru.nextInt();
            if (nilai >= 90) {
                System.out.println("Nilai A");
            } else if (nilai >= 80) {
                System.out.println("nilai B");
            } else if (nilai >= 70) {
                System.out.println("Totalnilai C");
            } else if (nilai >= 60) {
                System.out.println("Nilai D");
            } else {
                System.out.println("Nilai E");
            }
    }
}
