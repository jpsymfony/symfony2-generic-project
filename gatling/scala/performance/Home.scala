package jpsymfony.performance

import java.util.Calendar
import java.util.concurrent.atomic.AtomicInteger

import io.gatling.core.Predef._
import io.gatling.http.Predef._
import scala.concurrent.duration._

class Home extends Simulation {

    val testName = "home"
    val config = new jpsymfony.Config(testName)

    val calendar = Calendar.getInstance()
    val startDate: Long = calendar.getTimeInMillis() / 1000
    val requestNumber = new AtomicInteger()

    val httpConf = http
    .baseURL("http://" + config.host)

    val scn = scenario(config.testLabel)
    .exec(http("arrivée sur le site").get("/?_timestamp=(startDate + requestNumber.incrementAndGet()).toString)"))

    setUp(scn.inject(rampUsers(config.nbUsers) over (config.rampTimeSeconds seconds))
    .protocols(httpConf))
    .assertions(global.responseTime.min.greaterThan(config.minResponseTime))
    .assertions(global.responseTime.mean.lessThan(config.meanResponseTime))
    .assertions(global.responseTime.max.lessThan(config.maxResponseTime))
    .assertions(global.failedRequests.percent.is(config.allowedFailurePercent))

}
