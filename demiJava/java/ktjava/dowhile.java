public class dowhile {
    public static void main(String[]args){
        int a=1;
        boolean kondisi=true;
        do{
            System.out.println("looping ke "+a);
            if(a==10){
                kondisi=false;
            }
            a++;
        }while(kondisi);
        System.out.println("kobndisi selesai");
    }
}
