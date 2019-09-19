# zen-mode-daemon

Experimental browser to show all the web in "reader view"

---

## Maven

  $ mvn compile

  $ mvn dependency:resolve

  $ mvn exec:java


Package:

  $ mvn package

  $ java -cp target/my-app-1.0-SNAPSHOT.jar com.mycompany.app.App

## Web page types

An algorithm should be able to see the difference between:

* Search engine web page
* Search engine result page/link list
* Article
* Video
* Forum thread/comment thread

Naturla language processing, NLP?

Training set?

## Filter search result links

Split by avarage text length?

## Notes

Boilerplate Detection using Shallow Text Features

https://www.l3s.de/~kohlschuetter/publications/wsdm187-kohlschuetter.pdf

CleanEval: a competition for cleaning webpages (old)

https://cleaneval.sigwac.org.uk/lrec08-cleaneval.pdf

Firefox reader view code: https://github.com/mozilla/readability

Java port: https://github.com/chimbori/crux

Java NLP: https://opennlp.apache.org/

PHP lib Graby: https://github.com/j0k3r/graby

OCaml web scraping: https://github.com/yannham/mechaml
