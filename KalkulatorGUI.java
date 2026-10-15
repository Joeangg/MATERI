import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class KalkulatorGUI extends JFrame implements ActionListener {
    private JTextField display;
    private double num1 = 0, num2 = 0, result = 0;
    private char operator;

    public KalkulatorGUI() {
        // Pengaturan Frame
        setTitle("Kalkulator Java Swing");
        setSize(300, 400);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        setLayout(new BorderLayout());

        // Layar Display
        display = new JTextField();
        display.setFont(new Font("Arial", Font.BOLD, 24));
        display.setEditable(false);
        display.setHorizontalAlignment(JTextField.RIGHT);
        add(display, BorderLayout.NORTH);

        // Panel Tombol
        JPanel panel = new JPanel();
        panel.setLayout(new GridLayout(4, 4, 5, 5));

        // Daftar Tombol
        String[] buttons = {
            "7", "8", "9", "/",
            "4", "5", "6", "*",
            "1", "2", "3", "-",
            "0", "C", "=", "+"
        };

        for (String text : buttons) {
            JButton button = new JButton(text);
            button.setFont(new Font("Arial", Font.BOLD, 18));
            button.addActionListener(this);
            panel.add(button);
        }

        add(panel, BorderLayout.CENTER);
    }

    @Override
    public void actionPerformed(ActionEvent e) {
        String command = e.getActionCommand();

        // Jika tombol angka
        if ((command.charAt(0) >= '0' && command.charAt(0) <= '9')) {
            display.setText(display.getText() + command);
        } 
        // Tombol Clear (C)
        else if (command.equals("C")) {
            display.setText("");
            num1 = num2 = result = 0;
            operator = '\u0000';
        } 
        // Tombol Operator (+, -, *, /)
        else if (command.equals("+") || command.equals("-") || command.equals("*") || command.equals("/")) {
            if (!display.getText().isEmpty()) {
                num1 = Double.parseDouble(display.getText());
                operator = command.charAt(0);
                display.setText("");
            }
        } 
        // Tombol Sama Dengan (=)
        else if (command.equals("=")) {
            if (!display.getText().isEmpty() && operator != '\u0000') {
                num2 = Double.parseDouble(display.getText());
                switch (operator) {
                    case '+':
                        result = num1 + num2;
                        break;
                    case '-':
                        result = num1 - num2;
                        break;
                    case '*':
                        result = num1 * num2;
                        break;
                    case '/':
                        if (num2 != 0) {
                            result = num1 / num2;
                        } else {
                            display.setText("Error (Div by 0)");
                            return;
                        }
                        break;
                }
                // Format agar jika bulat tidak ada .0 yang mengganggu
                if (result == (long) result) {
                    display.setText(String.format("%d", (long) result));
                } else {
                    display.setText(String.valueOf(result));
                }
                operator = '\u0000';
            }
        }
    }

    public static void main(String[] args) {
        // Menjalankan GUI di Event Dispatch Thread
        SwingUtilities.invokeLater(() -> {
            new KalkulatorGUI().setVisible(true);
        });
    }
}
