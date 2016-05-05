package jpsymfony

import java.io.FileInputStream
import java.io.IOException
import java.io.InputStream
import java.util.Properties

class Config(val testName: String) {

    private var properties: Properties= _

    properties = new java.util.Properties()
    var input: InputStream = null
    try {
        input = new FileInputStream("properties/test-parameters.properties")
        properties.load(input)
    } catch {
            case ex: IOException => ex.printStackTrace()
    } finally {
            if (input != null) {
                try {
                    input.close()
                } catch {
                    case e: IOException => e.printStackTrace()
                }
            }
        }

    val platform = properties.getProperty("platform")
    val host = properties.getProperty("platform." + platform + ".host")

    def get(name: String): String = {
        val propertyName = testName + "." + platform + "." + name
        val propertyValue = properties.getProperty(propertyName)
        if (propertyValue == null) {
            throw new Error("Property not defined: " + propertyName)
        }
        return propertyValue
    }

    def getInt(name: String): Int = {
        return get(name).toInt
    }

    def nbUsers(): Int = { return getInt("users") }
    def rampTimeSeconds(): Int = { return getInt("rampTimeSeconds") }
    def minResponseTime(): Int = { return getInt("minResponseTime") }
    def meanResponseTime(): Int = { return getInt("meanResponseTime") }
    def maxResponseTime(): Int = { return getInt("maxResponseTime") }
    def allowedFailurePercent(): Int = { return getInt("allowedFailurePercent") }

    def usersPerSecond(): Double = {
        return ((1.0*nbUsers.intValue())/rampTimeSeconds.intValue())
    }

    def testLabel(): String = {
        return testName + " - " + usersPerSecond + " users per second on " + platform
    }

}
