package www.aboutyun.com;
import java.io.IOException;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.input.TextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;

public class HbaseMain {

        //static final String INPUT_PATH = "hdfs://localhost:9000/aboutyun/testlog.txt";
		static final String INPUT_PATH = "/home/ch-hduser/myfile/test.txt";
        static final String OUT_PATH = "hdfs://localhost:9000/aboutyunout/outdata06";
        
        public static void main(String[] args) throws IOException,
                        InterruptedException, ClassNotFoundException {

                // 主类
                Configuration conf = new Configuration();
                Job job = Job.getInstance(conf, HbaseMain.class.getSimpleName());
                job.setJarByClass(HbaseMain.class);
                // 寻找输入
                FileInputFormat.setInputPaths(job, INPUT_PATH);
                // 1.2对输入数据进行格式化处理的类
                job.setInputFormatClass(TextInputFormat.class);
                job.setMapperClass(HbaseMap.class);
                // 1.2指定map输出类型<key,value>类型
                job.setMapOutputKeyClass(Text.class);
                job.setMapOutputValueClass(LongWritable.class);
                job.setNumReduceTasks(0);
                // 指定输出路径
                FileOutputFormat.setOutputPath(job, new Path(OUT_PATH));
                
                job.waitForCompletion(true);

        }
}
