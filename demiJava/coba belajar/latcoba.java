import java.util.Scanner;
class latcoba {
    public static void main(String[] args) {
   Scanner a = new Scanner(System.in);
   System.out.print("masukan angka :");
    var i =a.nextInt(); 
        for(;  i <= 20; i++) {

    if(i % 2 == 0) {
        System.out.println("genap"+i);
    }else{
        System.out.println("ganjil"+i);
    }
    }
    }
}