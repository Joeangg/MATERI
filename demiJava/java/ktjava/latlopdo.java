import java.util.Scanner;
class latlopdo{
    public static void main(String[] args) {
        Scanner a = new Scanner(System.in);
        System.out.print("Masukkan nilai awal: ");
        int aw=a.nextInt();
        System.out.print("Masukkan nilai akhir: ");
        int ak=a.nextInt();
        int total = 0;
        do{
            total = total +aw;
             System.out.println(" ditambah " + aw +" jadi "+ total );
            aw++;
        } while (aw<=ak);
    
    }
      
}