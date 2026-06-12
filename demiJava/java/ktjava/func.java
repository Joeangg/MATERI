class func{
    public static void main(String[] args) {
        System.out.println();
        // z  = (a +c) a
        int a =5;
        int z = hitung(a);
        
        System.out.println("nilai a = " + a +  " maka z = "+z);
    
        a =10;
        z = hitung(a);
        System.out.println("nilai a = " + a +  " maka z = "+z);

         a =15;
         z = hitung(a);
        System.out.println("nilai a = " + a +  " maka z = "+z);
    }
         static int hitung (int input){
            int hasil;
            hasil = (input +2) *input;
            return hasil;
        
    }
}