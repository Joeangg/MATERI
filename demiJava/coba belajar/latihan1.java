import java.util.Scanner;
class latihan1{
    public static void main(String[] args) {
        Scanner a =new Scanner(System.in);
        System.out.print("masukan nama anda: ");
        String nama =a.nextLine();
        System.out.print("masukan umur anda: ");
        int umur =a.nextInt();
        if (umur >=17){
            System.out.println("selamat datang di partty"+nama);
        }else{
            System.out.println("belum bisa masuk sob\n");           
            System.out.println("lewat jalan bawah?(y/n)");
            System.out.println("masukan pilihan anda:\n ");
            String pilihan =a.nextLine();
            if (pilihan.equals("y")){
                System.out.println("selamat datang di partty"+nama);
                }else{System.out.println("bye"+nama);
        }
          
 
        }
    }
}
    
