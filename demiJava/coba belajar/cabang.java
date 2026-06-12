import java.util.*;
public class cabang {
    public static void main(String[] args) {
        
        Scanner a=new Scanner(System.in);
        System.out.print("masukan nilai :");
        int nilai=a.nextInt();
        System.out.println("apa sudah mengerjakan tugas?");
        String tugas=a.next();
        
        if (nilai <75){
            System.out.println("maaf tidak lulus");          
        }else if(nilai >45){
            System.out.println("cek nilai tugas");
        }if (tugas.equals("ya")){
            System.out.println("selamat anda lulus dengan nilai tugas");
        }else{
            System.out.println("silahkan remedial");
        }if(nilai >=85){
            System.out.println("selamat anda mendapar nilai tambahan di uts");

        }if (nilai >=90){
            System.out.println("anda tidak perlu mengikuti uts");

        }
    }
}