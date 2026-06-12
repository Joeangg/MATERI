import java.util.Scanner;
public class latloop {
    public static void main(String[] args) {
        Scanner input = new Scanner(System.in);
        System.out.print("Masukkan nilai awal: ");
        int n = input.nextInt();
        System.out.print("Masukkan nilai akhir: ");
        int m = input.nextInt();
       
        var total=0;

         while(n<=m){
         total = total+n;
         System.out.println("ditambah " +n + " jadi " + total);
            n++; 

         
        }
        
    }
    
}
