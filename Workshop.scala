import org.apache.spark.sql._

/**
 * Spark solution for {@link https://github.com/intraworlds/workshop-php}.
 * ==Start==
 * Run the Spark Shell and...
 * {{{
 * scala> :load Workshop.scala
 * scala> Workshop.main(null)
 * }}}
 */
object Workshop {

  def main(args: Array[String]) {
    val spark = SparkSession.builder.appName("Workshop").master("local[*]").getOrCreate()
    val rslt = spark.read.textFile("example.log")
        .map(line => {
            val pattern = """\[(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})\]\s[a-z.]+([A-Z]+):\s(.*)""".r
            val pattern(datetime, level, message) = line
            (datetime, level, message)
        })
        .filter(entry => entry._2 != "DEBUG")
        .groupBy("_2").count()

    rslt.show()
    spark.stop()
  }

}
