public class Method {
    public  static void main(String[] args) {
        hello();
        mePar(3);
        info("jo",21);
        kentang(3,4);
        int sisa;
        sisa=kentang(3,4)-2;
        System.out.println("sisa kentang :"+ sisa);
        hitungBaru(3,5);
    }
    static void hello(){
        System.out.println("hello");
    }
    // methode 
    static void mePar(int b){
        int a;
        a=b+1;
        System.out.println(a);
    }
    // methode parameter
    static void info(String nama, int umur){
        System.out.println("nama saya "+nama+" umur saya "+umur);
    }
    // method return value
    static int kentang(int x,int y){
        int z;
        return z=x+y;
    }
    // metode overloading
    static int hitungBaru(){
  return 0;
    }
static int hitungBaru(int a){
  return a;
    }
    static int hitungBaru(int a,int b){
  return a+b;
    }
}
