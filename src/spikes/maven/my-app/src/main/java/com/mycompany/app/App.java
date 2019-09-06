package com.mycompany.app;

import java.net.*;
import java.io.*;
import org.jsoup.*;
import org.jsoup.nodes.*;

/**
 * Hello world!
 *
 */
public class App 
{
    public static void main( String[] args ) throws IOException
    {
        //System.out.println( "Hello World!" );
        //String html = "<html><head><title>First parse</title></head>"
              //+ "<body><p>Parsed HTML into a doc.</p></body></html>";
        //Document doc = Jsoup.parse(html);

        ServerSocket welcomeSocket = new ServerSocket(777);

        while (true) {
            Socket connectionSocket = welcomeSocket.accept();
            InputStreamReader inputStreamReader = new InputStreamReader(connectionSocket.getInputStream());
            BufferedReader inFromClient = new BufferedReader(inputStreamReader);
            DataOutputStream outToClient = new DataOutputStream(connectionSocket.getOutputStream());
            String clientSentence = inFromClient.readLine();
            if (clientSentence != null) {
                System.out.println("Received: " + clientSentence);
                outToClient.writeBytes(clientSentence);
            } else {
                System.out.println("nothing from the client");
                System.exit(0);
            }
        }
    }
}
