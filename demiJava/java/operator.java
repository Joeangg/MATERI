class operator {
    public static void main() {
        
  int a=10;
  System.out.println("nilai dari a "+a);
  int b=20;
  System.out.println("nilai dari b "+b);

    System.out.println("nilai a ditambah b ="+a+b); 
    System.out.println("nilai a di kurang b ="+ (a-b));
    System.out.println("nilai a di kali b ="+a*b);
    System.out.println("nilai a di bagi b ="+a/b);
    System.out.println("nilai a di modulus b ="+(a%b));

    System.out.println("bisa juga  a=+b ="+(a+=b));
    System.out.println("bisa juga  a=-b ="+(a-=b));
    System.out.println("nilai a increment"+a++);
    /*menambahkan 1 setelah di lakuakn operasi */
     System.out.println("nilai a increment belakang"+(++a));
     /*menambahkan 1 sebelum */
    System.out.println("nilai a decrement"+a--);
        System.out.println("nilai a decrement belakang"+(--a));
    /* intinya dalam kalaimat before after */
   int  hasil=++a+b;
    System.out.println("hasil dari a+b ="+hasil);
   int  hasil2=(a++)+b;
    System.out.println("hasil dari a+b ="+hasil2);
   int c  =11;
   System.out.println("nilai c ="+c++);
    System.out.println("nilai c ="+c);    
 }
}
