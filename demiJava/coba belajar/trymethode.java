class trymethode{
public static void main(String[] args) {
    System.out.println("--- Table of 3 ---");
    printTable(3);    
    System.out.println("--- Table of 7 ---");
    printTable(7); 
}
    static void printTable(int number){
        for(int i =1; i<=5; i++){
        int result = number * i;
        System.out.println(number + " x " + i + " = " + result);
        }    
 }
}