package com.mycompany.app;

import java.net.URL;
import org.jsoup.*;
import org.jsoup.nodes.*;

/**
 * Hello world!
 *
 */
public class App 
{
    public static void main( String[] args )
    {
        System.out.println( "Hello World!" );
        String html = "<html><head><title>First parse</title></head>"
              + "<body><p>Parsed HTML into a doc.</p></body></html>";
        Document doc = Jsoup.parse(html);
    }
}
