package com.mycompany.app;

import java.net.URL;
import org.dom4j.Document;
import org.dom4j.DocumentException;
import org.dom4j.io.SAXReader;

/**
 * Hello world!
 *
 */
public class App 
{
    public static void main( String[] args )
    {
        System.out.println( "Hello World!" );
        SAXReader reader = new SAXReader();
        String url = "https://google.com";
        try {
            Document document = reader.read(url);
        } catch (DocumentException ex) {
            System.out.println("exception: " + ex);
        }
    }
}
