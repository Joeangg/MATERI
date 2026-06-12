import javax.swing.*;
import java.awt.*;

public class ShowImageLoop {
    public static void main(String[] args) {
        JFrame frame = new JFrame("Tampilkan Gambar 3 Detik");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(400, 300);

        JLabel label = new JLabel();
        label.setHorizontalAlignment(JLabel.CENTER);
        ImageIcon icon = new ImageIcon("gambar.jpg"); // ganti dengan file gambar
        label.setIcon(icon);

        frame.add(label, BorderLayout.CENTER);
        frame.setVisible(true);

        int countdown = 3; // hitung mundur 3 detik
        do {
            System.out.println("Menampilkan gambar... " + countdown + " detik tersisa");
            try {
                Thread.sleep(1000); // jeda 1 detik
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
            countdown--; // decrement
        } while (countdown > 0);

        frame.dispose(); // tutup frame setelah loop selesai
        System.out.println("Gambar ditutup.");
    }
}
