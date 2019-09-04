import java.io.IOException;
import java.nio.file.FileSystems;
import java.nio.file.Path;
import java.nio.file.StandardWatchEventKinds;
import java.nio.file.WatchEvent;
import java.nio.file.WatchEvent.Kind;
import java.nio.file.WatchKey;
import java.nio.file.WatchService;

public class FileEventTest {

    public static void main(String[] args) throws InterruptedException {
        Path pathToWatch = FileSystems.getDefault().getPath("/tmp/java7");
        DirectoryWatcher dirWatcher = new DirectoryWatcher(pathToWatch);
        Thread dirWatcherThread = new Thread(dirWatcher);
        dirWatcherThread.start();
        
        // interrupt the program after 10 seconds to stop it.
        Thread.sleep(10000);
        dirWatcherThread.interrupt();
    }
}
